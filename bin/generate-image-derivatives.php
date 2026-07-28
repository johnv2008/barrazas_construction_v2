<?php

declare(strict_types=1);

/**
 * One-time / on-demand image derivative generator.
 *
 * Reads every source image under public/assets/images/, and writes
 * responsive derivatives (WebP + JPEG) into public/assets/images/derived/
 * plus a manifest at public/assets/images/derivatives.json that the
 * runtime helper (App\Helpers\Image) reads to emit <picture> markup.
 *
 * Why a CLI script and not a runtime service: one.com is shared hosting
 * with no Node, no build step and no persistent process. Derivative
 * generation is an offline operation; the output is committed as static
 * files and served directly by Apache. GD ships with PHP there, so this
 * needs nothing installed.
 *
 * Usage:
 *   php bin/generate-image-derivatives.php              # only what changed
 *   php bin/generate-image-derivatives.php --force      # rebuild everything
 *   php bin/generate-image-derivatives.php --dry-run    # report, write nothing
 *   php bin/generate-image-derivatives.php --prune      # drop orphaned output
 *   php bin/generate-image-derivatives.php --path=images/projects/foo.jpg
 *
 * Originals are never modified, renamed, or deleted. They remain the only
 * re-derivable source, and for several of these jobs they are the sole
 * surviving record of completed work.
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("This script is CLI-only.\n");
}

ini_set('memory_limit', '512M');

require __DIR__ . '/../app/Config/image-derivatives.php';

// ---------------------------------------------------------------------
// Configuration
// ---------------------------------------------------------------------

$root      = dirname(__DIR__);
$sourceDir = $root . '/public/assets/images';
$outputDir = $sourceDir . '/derived';
$manifest  = $sourceDir . '/derivatives.json';

/**
 * Target widths. A source is never upscaled — a 450px original emits only
 * the 480 "thumb" entry (clamped to 450), and nothing wider. "hero" is
 * therefore only produced when the source genuinely supports it.
 */
const WIDTHS = [
    'thumb'   => 480,
    'card'    => 900,
    'content' => 1400,
    'hero'    => 2000,
];

/**
 * These sources are already compressed — phone JPEGs that have been
 * through at least one lossy pass. Re-encoding them at a *higher*
 * quality than they were saved at produces a bigger file for no visible
 * gain, so the numbers below sit deliberately under the usual q82/q78
 * defaults. Measured on this library: q78/q72 keeps the full-width WebP
 * roughly 35-45% under the original with no artefacts at display size.
 */
const JPEG_QUALITY = 78;
const WEBP_QUALITY = 72;

/** Directories excluded from processing (already-optimised UI assets). */
const SKIP_DIRS = ['derived'];

/** Hard limits — refuse implausible input rather than exhaust memory. */
const MAX_SOURCE_BYTES = 8 * 1024 * 1024;
const MAX_SOURCE_EDGE  = 8000;

$options = getopt('', ['force', 'dry-run', 'prune', 'path:', 'help']);

if (isset($options['help'])) {
    fwrite(STDOUT, file_get_contents(__FILE__, false, null, 0, 1600));
    exit(0);
}

$force  = isset($options['force']);
$dryRun = isset($options['dry-run']);
$prune  = isset($options['prune']);
$only   = isset($options['path']) ? ltrim(str_replace('\\', '/', (string) $options['path']), '/') : null;

// ---------------------------------------------------------------------
// Capability detection — one.com's PHP build may differ from local
// ---------------------------------------------------------------------

if (!extension_loaded('gd')) {
    exit("FATAL: the gd extension is required and is not loaded.\n");
}

$canWebp = function_exists('imagewebp');
$canExif = function_exists('exif_read_data');

fwrite(STDOUT, sprintf(
    "GD ready. WebP: %s. EXIF: %s.%s\n\n",
    $canWebp ? 'yes' : 'NO (JPEG only)',
    $canExif ? 'yes' : 'NO (orientation left as-is)',
    $dryRun ? ' DRY RUN — nothing will be written.' : ''
));

if (!$canWebp) {
    fwrite(STDOUT, "WARNING: no WebP support. JPEG derivatives will still be written,\n"
        . "so the site stays correct, but the payload win will be much smaller.\n\n");
}

// ---------------------------------------------------------------------
// Collect sources
// ---------------------------------------------------------------------

if (!is_dir($sourceDir)) {
    exit("FATAL: {$sourceDir} does not exist.\n");
}

if (!$dryRun && !is_dir($outputDir) && !mkdir($outputDir, 0755, true) && !is_dir($outputDir)) {
    exit("FATAL: could not create {$outputDir}.\n");
}

$sources = [];
$walker  = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($sourceDir, FilesystemIterator::SKIP_DOTS)
);

foreach ($walker as $file) {
    /** @var SplFileInfo $file */
    if (!$file->isFile()) {
        continue;
    }

    $absolute = str_replace('\\', '/', $file->getPathname());
    $relative = ltrim(str_replace(str_replace('\\', '/', $sourceDir), '', $absolute), '/');

    $topLevel = explode('/', $relative)[0];
    if (in_array($topLevel, SKIP_DIRS, true)) {
        continue;
    }

    if (!preg_match('/\.(jpe?g|png)$/i', $relative)) {
        continue;
    }

    if ($only !== null && !str_ends_with('images/' . $relative, $only) && $relative !== $only) {
        continue;
    }

    $sources[$relative] = $absolute;
}

ksort($sources);

if ($sources === []) {
    exit("No source images matched.\n");
}

// ---------------------------------------------------------------------
// Process
// ---------------------------------------------------------------------

$existing = is_file($manifest)
    ? (json_decode((string) file_get_contents($manifest), true) ?: [])
    : [];

$manifestImages = $existing['images'] ?? [];
$focalOverrides = image_focal_overrides();

$stats = [
    'processed' => 0,
    'skipped'   => 0,
    'rejected'  => 0,
    'written'   => 0,
    'srcBytes'  => 0,
    'outBytes'  => 0,
];

$producedFiles = [];

foreach ($sources as $relative => $absolute) {
    $key       = 'images/' . $relative;
    $sourceHash = (string) md5_file($absolute);
    $sizeBytes  = (int) filesize($absolute);

    // --- Validation: MIME by content, never by extension -------------
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime  = (string) $finfo->file($absolute);

    if (!in_array($mime, ['image/jpeg', 'image/png'], true)) {
        fwrite(STDOUT, sprintf("  REJECT %-42s unsupported MIME %s\n", $relative, $mime));
        $stats['rejected']++;
        continue;
    }

    if ($sizeBytes > MAX_SOURCE_BYTES) {
        fwrite(STDOUT, sprintf("  REJECT %-42s %s MB exceeds the %d MB cap\n",
            $relative, round($sizeBytes / 1048576, 1), MAX_SOURCE_BYTES / 1048576));
        $stats['rejected']++;
        continue;
    }

    $probe = @getimagesize($absolute);
    if ($probe === false) {
        fwrite(STDOUT, sprintf("  REJECT %-42s not a readable image\n", $relative));
        $stats['rejected']++;
        continue;
    }

    if ($probe[0] > MAX_SOURCE_EDGE || $probe[1] > MAX_SOURCE_EDGE) {
        fwrite(STDOUT, sprintf("  REJECT %-42s %dx%d exceeds the %dpx edge cap\n",
            $relative, $probe[0], $probe[1], MAX_SOURCE_EDGE));
        $stats['rejected']++;
        continue;
    }

    // --- Skip unchanged sources unless forced -------------------------
    $previous = $manifestImages[$key] ?? null;
    if (!$force && $previous !== null && ($previous['hash'] ?? null) === $sourceHash) {
        // Still confirm the files it claims are actually present.
        $intact = true;
        foreach ($previous['variants'] ?? [] as $variant) {
            foreach (['jpg', 'webp'] as $fmt) {
                if (!empty($variant[$fmt]) && !is_file($root . '/public/assets/' . $variant[$fmt])) {
                    $intact = false;
                    break 2;
                }
            }
        }

        if ($intact) {
            foreach ($previous['variants'] ?? [] as $variant) {
                foreach (['jpg', 'webp'] as $fmt) {
                    if (!empty($variant[$fmt])) {
                        $producedFiles[] = $root . '/public/assets/' . $variant[$fmt];
                    }
                }
            }
            $stats['skipped']++;
            continue;
        }
    }

    // --- Load -------------------------------------------------------
    $image = $mime === 'image/png'
        ? @imagecreatefrompng($absolute)
        : @imagecreatefromjpeg($absolute);

    if ($image === false) {
        fwrite(STDOUT, sprintf("  REJECT %-42s GD could not decode it\n", $relative));
        $stats['rejected']++;
        continue;
    }

    // --- EXIF orientation --------------------------------------------
    // 20 of these are phone photos; several carry a rotation flag. Apply
    // it now so the derivative is upright, then let the re-encode drop
    // all EXIF (which also strips GPS — these are photos of clients'
    // homes and the coordinates have no business being published).
    if ($canExif && $mime === 'image/jpeg') {
        $image = apply_exif_orientation($image, $absolute);
    }

    $srcW = imagesx($image);
    $srcH = imagesy($image);

    $focal = $focalOverrides[$key] ?? ['x' => 0.5, 'y' => 0.5];

    $slug     = derive_slug($relative);
    $shortHash = substr($sourceHash, 0, 8);

    $variants = [];

    foreach (WIDTHS as $label => $targetWidth) {
        // Never upscale. A 450px original produces one clamped variant.
        $width = min($targetWidth, $srcW);

        if ($width < 1) {
            continue;
        }

        // Skip a label whose clamped width duplicates one already emitted.
        if (isset($variants[$width])) {
            continue;
        }

        $height = (int) max(1, (int) round($srcH * ($width / $srcW)));

        $entry = ['label' => $label, 'w' => $width, 'h' => $height];

        $basename = sprintf('%s-%s-%dw', $slug, $shortHash, $width);

        // A raster fallback is always written, in the source's own family:
        // PNG stays PNG so transparency survives, JPEG stays JPEG.
        $isPng   = $mime === 'image/png';
        $fbExt   = $isPng ? 'png' : 'jpg';
        $jpgRel  = 'images/derived/' . $basename . '.' . $fbExt;
        $jpgPath = $root . '/public/assets/' . $jpgRel;

        if (!$dryRun) {
            $resized = resize_to($image, $width, $height, $isPng);

            if ($isPng) {
                imagepng($resized, $jpgPath, 8);
            } else {
                imagejpeg($resized, $jpgPath, JPEG_QUALITY);
            }

            if ($canWebp) {
                // WebP carries alpha, so the transparent logos are safe here.
                imagewebp($resized, $root . '/public/assets/images/derived/' . $basename . '.webp', WEBP_QUALITY);
            }

            imagedestroy($resized);
        }

        // Guard: a derivative must never be heavier than the file it
        // replaces. These sources are already compressed, and GD's PNG
        // encoder in particular regularly produces a *larger* file than
        // the original even at a smaller pixel size. When that happens,
        // discard the derivative and point the manifest at the original —
        // fewer bytes and higher quality, at the cost of a little extra
        // decode. Applies at every width, not just full width.
        $usedOriginal = false;
        if (!$dryRun && is_file($jpgPath) && filesize($jpgPath) >= $sizeBytes) {
            unlink($jpgPath);
            $entry['jpg'] = $key;
            $entry['jpgIsOriginal'] = true;
            $usedOriginal = true;
            $stats['outBytes'] += $sizeBytes;
        }

        if (!$usedOriginal) {
            $entry['jpg'] = $jpgRel;
            $producedFiles[] = $jpgPath;
            if (is_file($jpgPath)) {
                $stats['outBytes'] += (int) filesize($jpgPath);
            }
        }

        if ($canWebp) {
            $webpRel  = 'images/derived/' . $basename . '.webp';
            $webpPath = $root . '/public/assets/' . $webpRel;

            // Same guard as the JPEG path: never offer a WebP that is
            // heavier than the original it would replace.
            if (!$dryRun && is_file($webpPath) && filesize($webpPath) >= $sizeBytes) {
                unlink($webpPath);
            } else {
                $entry['webp'] = $webpRel;
                $producedFiles[] = $webpPath;
                if (is_file($webpPath)) {
                    $stats['outBytes'] += (int) filesize($webpPath);
                }
            }
        }

        $variants[$width] = $entry;
        $stats['written']++;
    }

    imagedestroy($image);
    ksort($variants);

    $manifestImages[$key] = [
        'hash'   => $sourceHash,
        'mime'   => $mime,
        'width'  => $srcW,
        'height' => $srcH,
        'focal'  => $focal,
        // Widest available variant, so callers can pick a sane default
        // and never request a size that was not produced.
        'maxWidth' => max(array_keys($variants)),
        'variants' => array_values($variants),
    ];

    $stats['processed']++;
    $stats['srcBytes'] += $sizeBytes;

    fwrite(STDOUT, sprintf(
        "  OK     %-42s %4dx%-4d -> %s\n",
        $relative,
        $srcW,
        $srcH,
        implode(', ', array_map(static fn ($v) => $v['w'] . 'w', array_values($variants)))
    ));
}

// ---------------------------------------------------------------------
// Prune orphans
// ---------------------------------------------------------------------

if ($prune && !$dryRun && is_dir($outputDir)) {
    $keep = array_flip(array_map(static fn ($p) => str_replace('\\', '/', $p), $producedFiles));
    $removed = 0;

    foreach (glob($outputDir . '/*.{jpg,webp}', GLOB_BRACE) ?: [] as $candidate) {
        if (!isset($keep[str_replace('\\', '/', $candidate)])) {
            unlink($candidate);
            $removed++;
        }
    }

    fwrite(STDOUT, "\nPruned {$removed} orphaned derivative(s).\n");
}

// ---------------------------------------------------------------------
// Write manifest
// ---------------------------------------------------------------------

ksort($manifestImages);

if (!$dryRun) {
    file_put_contents($manifest, json_encode([
        'note'   => 'Generated by bin/generate-image-derivatives.php. Do not hand-edit.',
        'widths' => WIDTHS,
        'images' => $manifestImages,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");
}

fwrite(STDOUT, sprintf(
    "\n%d processed, %d unchanged, %d rejected. %d derivative files.\n"
    . "Source total %s MB -> derivative total %s MB.\n%s",
    $stats['processed'],
    $stats['skipped'],
    $stats['rejected'],
    $stats['written'],
    round($stats['srcBytes'] / 1048576, 2),
    round($stats['outBytes'] / 1048576, 2),
    $dryRun ? "DRY RUN — no files written.\n" : "Manifest: {$manifest}\n"
));

// ---------------------------------------------------------------------
// Helpers
// ---------------------------------------------------------------------

/**
 * Rotate/flip according to the EXIF orientation flag. Returns the original
 * resource untouched when there is no flag or the file has no EXIF block.
 */
function apply_exif_orientation(GdImage $image, string $path): GdImage
{
    $exif = @exif_read_data($path);

    if ($exif === false || empty($exif['Orientation'])) {
        return $image;
    }

    $rotate = static function (GdImage $img, float $deg): GdImage {
        $out = imagerotate($img, $deg, 0);
        if ($out === false) {
            return $img;
        }
        imagedestroy($img);
        return $out;
    };

    switch ((int) $exif['Orientation']) {
        case 2: imageflip($image, IMG_FLIP_HORIZONTAL); break;
        case 3: $image = $rotate($image, 180.0); break;
        case 4: imageflip($image, IMG_FLIP_VERTICAL); break;
        case 5: $image = $rotate($image, 270.0); imageflip($image, IMG_FLIP_HORIZONTAL); break;
        case 6: $image = $rotate($image, 270.0); break;
        case 7: $image = $rotate($image, 90.0); imageflip($image, IMG_FLIP_HORIZONTAL); break;
        case 8: $image = $rotate($image, 90.0); break;
    }

    return $image;
}

/**
 * High-quality downscale.
 *
 * PNG sources keep their alpha channel: the brand logos are transparent
 * line art and one of them sits on the dark footer, where a flattened
 * white background would be glaringly visible.
 */
function resize_to(GdImage $source, int $width, int $height, bool $keepAlpha): GdImage
{
    $canvas = imagecreatetruecolor($width, $height);

    if ($keepAlpha) {
        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);
        $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
        imagefilledrectangle($canvas, 0, 0, $width, $height, $transparent);
    }

    imagecopyresampled(
        $canvas,
        $source,
        0, 0, 0, 0,
        $width, $height,
        imagesx($source),
        imagesy($source)
    );

    return $canvas;
}

/**
 * Filesystem-safe, collision-resistant slug from the source path. Includes
 * the parent directory so images/projects/hero.jpg and images/brand/hero.jpg
 * cannot collide in the flat derived/ directory.
 */
function derive_slug(string $relative): string
{
    $withoutExt = preg_replace('/\.[^.]+$/', '', $relative) ?? $relative;
    $slug       = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $withoutExt) ?? $withoutExt);

    return trim($slug, '-');
}
