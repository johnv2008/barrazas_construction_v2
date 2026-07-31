<?php

declare(strict_types=1);

use App\Core\Config;
use App\Core\Nonce;
use App\Core\Router;

/** @var \App\Core\Request $request */
/**
 * Locate bootstrap/ — it may sit ABOVE the web root or INSIDE it.
 *
 * The preferred layout keeps app/, bootstrap/, database/, routes/ and
 * storage/ one level above the document root, where the web server cannot
 * reach them at all. Some shared hosts (one.com among them) do not let you
 * upload above the web root through their File Manager, so that layout is
 * simply unavailable to those accounts.
 *
 * Rather than make the person deploying fight their control panel, both
 * layouts are supported: prefer the sibling location, fall back to the
 * in-root one. APP_ROOT itself needs no special case — bootstrap/ is always
 * one level beneath it, so dirname(__DIR__) inside bootstrap/app.php
 * resolves correctly either way.
 *
 * The in-root layout is safe because every one of those folders ships a
 * `Require all denied` .htaccess, and public/.htaccess additionally refuses
 * to serve dotfiles and .env/.sql/.log/.md by extension.
 */
$appRoot = is_file(dirname(__DIR__) . '/bootstrap/app.php') ? dirname(__DIR__) : __DIR__;
$request = require $appRoot . '/bootstrap/app.php';

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

require APP_ROOT . '/routes/web.php';

$adminPath = (string) Config::get('app.admin_path', 'admin');
require APP_ROOT . '/routes/admin.php';

$router->dispatch($request);
