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

function base_url(string $path = ''): string
{
    $base = (string) Config::get('app.url', '');

    return $base . '/' . ltrim($path, '/');
}

function admin_url(string $path = ''): string
{
    $adminPath = (string) Config::get('app.admin_path', 'admin');

    return base_url($adminPath . '/' . ltrim($path, '/'));
}

function asset(string $path): string
{
    return '/assets/' . ltrim($path, '/');
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
