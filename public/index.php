<?php

declare(strict_types=1);

use App\Core\Config;
use App\Core\Nonce;
use App\Core\Router;

/** @var \App\Core\Request $request */
$request = require dirname(__DIR__) . '/bootstrap/app.php';

/*
 * Security headers, set in PHP so they apply even if Apache's headers
 * module is unavailable on a given host. Mirrored in public/.htaccess
 * for static assets and error documents that bypass this script.
 *
 * style-src allows 'unsafe-inline' because a handful of components use
 * one-off inline `style` attributes instead of extra utility classes;
 * script-src stays nonce-gated (no 'unsafe-inline') — the one inline
 * <script> the app renders (JSON-LD structured data) carries this
 * request's nonce via csp_nonce().
 */
$cspNonce = Nonce::get();

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
header(
    "Content-Security-Policy: default-src 'self'; script-src 'self' 'nonce-{$cspNonce}'; "
    . "style-src 'self' 'unsafe-inline'; img-src 'self' data:; font-src 'self'; "
    . "frame-ancestors 'self'; base-uri 'self'; form-action 'self'; object-src 'none'"
);

if ($request->isSecure()) {
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
}

$router = new Router();

require dirname(__DIR__) . '/routes/web.php';

$adminPath = (string) Config::get('app.admin_path', 'admin');
require dirname(__DIR__) . '/routes/admin.php';

$router->dispatch($request);
