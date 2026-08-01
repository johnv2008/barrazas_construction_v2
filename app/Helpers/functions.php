<?php

declare(strict_types=1);

use App\Core\Config;
use App\Helpers\Csrf;
use App\Services\SessionService;

/**
 * Small set of global helper functions used across views/controllers.
 * Kept intentionally short — anything more involved belongs in a
 * Service or Helper class.
 */

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function config(string $key, mixed $default = null): mixed
{
    return Config::get($key, $default);
}

/**
 * Absolute URL for a path.
 *
 * APP_URL is authoritative and should always be set in production: it is
 * what pins the site to a single hostname, which is the only thing that
 * makes <link rel="canonical"> meaningful when both www and apex resolve.
 *
 * When it is missing the origin is derived from the request instead. That
 * fallback exists because the failure it replaces was silent and expensive:
 * an empty APP_URL produced "/" for every canonical tag and a relative
 * Sitemap: line in robots.txt. Both are invalid rather than merely
 * suboptimal, and neither raises an error — the pages render perfectly and
 * simply stop being indexable correctly.
 */
function base_url(string $path = ''): string
{
    $base = rtrim((string) Config::get('app.url', ''), '/');

    if ($base === '') {
        $base = request_origin();
    }

    return $base . '/' . ltrim($path, '/');
}

/**
 * Scheme and host for the current request, for use only as a fallback.
 *
 * The Host header is client-controlled, so it is filtered to the hostname
 * character set before being echoed into a page. Without that, a poisoned
 * header could point canonical tags at someone else's domain.
 */
function request_origin(): string
{
    $host = (string) ($_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? '');
    $host = (string) preg_replace('/[^A-Za-z0-9.:\-]/', '', $host);

    if ($host === '') {
        return '';
    }

    // TLS is commonly terminated at a proxy on shared hosting, so $_SERVER
    // ['HTTPS'] alone would emit http:// canonicals on an https:// page.
    $forwarded = strtolower((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? ''));
    $https = $forwarded === 'https'
        || (($_SERVER['HTTPS'] ?? 'off') !== 'off' && ($_SERVER['HTTPS'] ?? '') !== '')
        || (string) ($_SERVER['SERVER_PORT'] ?? '') === '443';

    return ($https ? 'https' : 'http') . '://' . $host;
}

function admin_url(string $path = ''): string
{
    $adminPath = (string) Config::get('app.admin_path', 'admin');

    return base_url($adminPath . '/' . ltrim($path, '/'));
}

/**
 * Filesystem path to a file under the published asset root.
 *
 * Two deployment layouts have to work. In development the repository root
 * contains public/, so assets live at public/assets/. The shared host
 * flattens the contents of public/ INTO the web root, which makes app/ and
 * assets/ siblings and removes the public/ segment entirely. Callers pass a
 * path relative to public/ and get the right answer in both.
 */
function public_path(string $path = ''): string
{
    static $root = null;

    if ($root === null) {
        $base = defined('APP_ROOT') ? APP_ROOT : dirname(__DIR__, 2);
        $root = is_dir($base . '/public') ? $base . '/public' : $base;
    }

    return rtrim($root, '/') . '/' . ltrim($path, '/');
}

/**
 * Public URL for an asset, fingerprinted where caching demands it.
 *
 * Stylesheets and scripts get a modification-time query string because
 * .htaccess caches them for a year, and a year-long cache on an unversioned
 * filename means a design change never reaches anyone who has already
 * visited. Images are deliberately excluded: derived variants already carry
 * a content hash in their filename, and some social scrapers treat a
 * query string on og:image as a different resource.
 */
function asset(string $path): string
{
    $path = ltrim($path, '/');
    $url = '/assets/' . $path;

    if (preg_match('/\.(css|js)$/i', $path) !== 1) {
        return $url;
    }

    $file = public_path('assets/' . $path);
    $stamp = is_file($file) ? filemtime($file) : false;

    return $stamp === false ? $url : $url . '?v=' . $stamp;
}

/**
 * Responsive <picture> markup for an image under public/assets/.
 *
 * Emits WebP with a JPEG fallback, a full srcset/sizes pair, explicit
 * width/height, and object-position when a focal point is configured.
 * Falls back to a plain <img> of the original if the image has no
 * generated derivatives yet, so a missing manifest degrades rather than
 * breaks.
 *
 *   responsive_image('images/projects/service-kitchen.jpg', [
 *       'alt'      => 'Remodeled kitchen…',
 *       'sizes'    => '(min-width: 1024px) 46vw, 100vw',
 *       'priority' => true,   // at most one per page wins
 *   ]);
 *
 * Regenerate derivatives with: php bin/generate-image-derivatives.php
 */
function responsive_image(string $path, array $options = []): string
{
    return \App\Helpers\Image::picture($path, $options);
}

/**
 * The contractor license as displayed to the public, e.g.
 * "CSLB License #1234567". Returns '' when no number is configured so
 * callers can skip the whole line rather than print a bare label.
 */
function license_line(): string
{
    $number = trim((string) Config::get('business.license_number', ''));

    if ($number === '') {
        return '';
    }

    $authority = trim((string) Config::get('business.license_authority', ''));

    return trim($authority . ' License #' . $number);
}

function csrf_field(): string
{
    return Csrf::field();
}

function current_path(): string
{
    return (new \App\Core\Request())->path();
}

function csp_nonce(): string
{
    return \App\Core\Nonce::get();
}

/**
 * Read a single flashed input value, once per request. Never use for
 * password fields.
 */
function old(string $key, string $default = ''): string
{
    static $cache = null;

    if ($cache === null) {
        $cache = SessionService::pullOldInput();
    }

    $value = $cache[$key] ?? $default;

    return is_string($value) ? $value : $default;
}

/**
 * Read all flash messages for the current request, once.
 *
 * @return array<string, array<int, string>>
 */
function flashes(): array
{
    static $cache = null;

    if ($cache === null) {
        $cache = SessionService::pullFlashes();
    }

    return $cache;
}
