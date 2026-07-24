<?php

declare(strict_types=1);

/**
 * Router script for PHP's built-in development server only:
 *   php -S localhost:8000 router.php
 * (run from inside public/). Mirrors the same "serve real files
 * directly, otherwise hand off to index.php" behavior that
 * public/.htaccess provides under Apache. Never used in production —
 * one.com serves this application through Apache + .htaccess.
 */

$requestedPath = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/');
$fullPath = __DIR__ . $requestedPath;

if ($requestedPath !== '/' && is_file($fullPath)) {
    return false;
}

require __DIR__ . '/index.php';
