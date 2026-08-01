<?php

declare(strict_types=1);

namespace App\Helpers;

/**
 * Emits responsive <picture> markup from the derivative manifest written
 * by bin/generate-image-derivatives.php.
 *
 * Design rules this enforces so callers cannot get them wrong:
 *
 *  - Every image carries explicit width/height, so the browser reserves
 *    the box before the bytes arrive and the layout never shifts (CLS).
 *  - Exactly one image per page may be the LCP candidate. Passing
 *    `priority => true` sets fetchpriority="high" AND loading="eager";
 *    everything else is lazy. The caller cannot accidentally mark two.
 *  - WebP is offered first with a JPEG fallback in the same <picture>,
 *    so a browser or host without WebP still gets a correct image.
 *  - If an image is missing from the manifest the original is emitted
 *    unchanged rather than a broken <img>. The site degrades to exactly
 *    its pre-optimisation behaviour instead of breaking.
 */
final class Image
{
    /** @var array<string, mixed>|null */
    private static ?array $manifest = null;

    /** Tracks the single priority image so a second one cannot claim it. */
    private static bool $priorityClaimed = false;

    /**
     * @param string $path   Path relative to public/assets/, e.g.
     *                       "images/projects/service-kitchen.jpg"
     * @param array{
     *     alt?: string,
     *     sizes?: string,
     *     class?: string,
     *     priority?: bool,
     *     width?: int|null,
     *     height?: int|null,
     *     decorative?: bool
     * } $options
     */
    public static function picture(string $path, array $options = []): string
    {
        $path  = self::normalize($path);
        $entry = self::entry($path);

        $decorative = (bool) ($options['decorative'] ?? false);
        $alt        = $decorative ? '' : (string) ($options['alt'] ?? '');
        $class      = trim((string) ($options['class'] ?? ''));
        $sizes      = (string) ($options['sizes'] ?? '100vw');

        // Only the first caller asking for priority gets it.
        $wantsPriority = (bool) ($options['priority'] ?? false);
        $priority      = $wantsPriority && !self::$priorityClaimed;
        if ($priority) {
            self::$priorityClaimed = true;
        }

        $loading  = $priority ? 'eager' : 'lazy';
        $fetchPri = $priority ? ' fetchpriority="high"' : '';
        // A lazy image should not also be decoded synchronously.
        $decoding = $priority ? 'sync' : 'async';

        if ($entry === null) {
            return self::fallbackImg($path, $alt, $class, $loading, $fetchPri, $decoding, $options);
        }

        $variants = $entry['variants'] ?? [];
        if ($variants === []) {
            return self::fallbackImg($path, $alt, $class, $loading, $fetchPri, $decoding, $options);
        }

        // The widest variant backs the <img> src and supplies the
        // intrinsic width/height attributes.
        $largest = $variants[count($variants) - 1];

        $jpegSrcset = [];
        $webpSrcset = [];
        $seen       = ['jpg' => [], 'webp' => []];
        $sourceW    = (int) ($entry['width'] ?? 0);

        foreach ($variants as $variant) {
            // When the generator discarded a derivative because it came out
            // heavier than the source, the manifest points at the original.
            // Its descriptor must be the original's *real* width — declaring
            // one file at two different widths is malformed srcset, and the
            // browser would be choosing between candidates that don't exist.
            foreach (['jpg', 'webp'] as $format) {
                if (empty($variant[$format])) {
                    continue;
                }

                $url = self::assetUrl($variant[$format]);

                if (isset($seen[$format][$url])) {
                    continue;
                }
                $seen[$format][$url] = true;

                $descriptor = ($format === 'jpg' && !empty($variant['jpgIsOriginal']) && $sourceW > 0)
                    ? $sourceW
                    : (int) $variant['w'];

                if ($format === 'jpg') {
                    $jpegSrcset[$descriptor] = $url . ' ' . $descriptor . 'w';
                } else {
                    $webpSrcset[$descriptor] = $url . ' ' . $descriptor . 'w';
                }
            }
        }

        ksort($jpegSrcset);
        ksort($webpSrcset);
        $jpegSrcset = array_values($jpegSrcset);
        $webpSrcset = array_values($webpSrcset);

        // A per-call focal point overrides the manifest. The manifest holds
        // one value per FILE, which is right when an image is used as a
        // room; it is not enough when the same file is cropped to a
        // different subject in a different frame (the Kitchen material band
        // crops one photograph to its tile, and another to its hardware).
        // Falls back to the manifest, then to dead centre.
        $focal = $options['focal'] ?? $entry['focal'] ?? ['x' => 0.5, 'y' => 0.5];
        $style = '';
        if (abs(((float) $focal['x']) - 0.5) > 0.001 || abs(((float) $focal['y']) - 0.5) > 0.001) {
            $style = sprintf(
                ' style="object-position:%s%% %s%%"',
                self::num(((float) $focal['x']) * 100),
                self::num(((float) $focal['y']) * 100)
            );
        }

        $imgTag = sprintf(
            '<img src="%s"%s alt="%s" width="%d" height="%d" loading="%s" decoding="%s"%s%s%s>',
            self::e(self::assetUrl($largest['jpg'] ?? $path)),
            $jpegSrcset !== [] ? ' srcset="' . self::e(implode(', ', $jpegSrcset)) . '"' : '',
            self::e($alt),
            (int) $largest['w'],
            (int) $largest['h'],
            $loading,
            $decoding,
            $jpegSrcset !== [] ? ' sizes="' . self::e($sizes) . '"' : '',
            $fetchPri,
            $style
        );

        if ($class !== '') {
            $imgTag = str_replace('<img ', '<img class="' . self::e($class) . '" ', $imgTag);
        }

        if ($webpSrcset === []) {
            return $imgTag;
        }

        return sprintf(
            '<picture><source type="image/webp" srcset="%s" sizes="%s">%s</picture>',
            self::e(implode(', ', $webpSrcset)),
            self::e($sizes),
            $imgTag
        );
    }

    /**
     * Intrinsic dimensions of a source image, for callers that need the
     * numbers without the markup. Null when unknown.
     *
     * @return array{width: int, height: int}|null
     */
    public static function dimensions(string $path): ?array
    {
        $entry = self::entry(self::normalize($path));

        if ($entry === null || empty($entry['width']) || empty($entry['height'])) {
            return null;
        }

        return ['width' => (int) $entry['width'], 'height' => (int) $entry['height']];
    }

    /** Resets the once-per-request priority claim. Used by tests. */
    public static function resetPriority(): void
    {
        self::$priorityClaimed = false;
    }

    // -----------------------------------------------------------------

    /**
     * Accepts either a manifest key ("images/projects/x.jpg") or a
     * already-resolved asset URL ("/assets/images/projects/x.jpg"), so
     * callers can pass the output of asset() without change.
     */
    private static function normalize(string $path): string
    {
        $path = ltrim($path, '/');

        if (str_starts_with($path, 'assets/')) {
            $path = substr($path, strlen('assets/'));
        }

        return $path;
    }

    /** @return array<string, mixed>|null */
    private static function entry(string $path): ?array
    {
        if (self::$manifest === null) {
            // public_path() resolves the development layout (public/assets)
            // and the flattened shared-host layout (assets) alike. Hardcoding
            // the former here is what broke production, and it broke silently:
            // a manifest that fails to load raises nothing, it just degrades
            // every image to a bare <img> with no srcset, no WebP and no
            // intrinsic width/height. The pages still render correctly — they
            // simply ship full-size originals and lose their CLS protection.
            $file = public_path('assets/images/derivatives.json');

            self::$manifest = is_file($file)
                ? (json_decode((string) file_get_contents($file), true)['images'] ?? [])
                : [];
        }

        return self::$manifest[$path] ?? null;
    }

    private static function fallbackImg(
        string $path,
        string $alt,
        string $class,
        string $loading,
        string $fetchPri,
        string $decoding,
        array $options
    ): string {
        $dims = '';
        if (!empty($options['width']) && !empty($options['height'])) {
            $dims = sprintf(' width="%d" height="%d"', (int) $options['width'], (int) $options['height']);
        }

        return sprintf(
            '<img%s src="%s" alt="%s"%s loading="%s" decoding="%s"%s>',
            $class !== '' ? ' class="' . self::e($class) . '"' : '',
            self::e(self::assetUrl($path)),
            self::e($alt),
            $dims,
            $loading,
            $decoding,
            $fetchPri
        );
    }

    private static function assetUrl(string $relative): string
    {
        return '/assets/' . ltrim($relative, '/');
    }

    private static function e(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /** Trims trailing zeros so 50.0 renders as "50". */
    private static function num(float $value): string
    {
        return rtrim(rtrim(number_format($value, 2, '.', ''), '0'), '.');
    }
}
