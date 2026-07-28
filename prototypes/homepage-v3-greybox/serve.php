<?php
/**
 * Greybox prototype dev server router — LOCAL PROTOTYPE ONLY.
 *
 * Run from the repository root:
 *   php -S localhost:8001 -t prototypes/homepage-v3-greybox prototypes/homepage-v3-greybox/serve.php
 *
 * This file exists solely so the prototype can reference the real project
 * photographs (needed to evaluate cropping and sequence) without duplicating
 * ~9MB of images into the prototype directory. It maps /img/* to the real
 * image directory, read-only, with a path-traversal guard.
 *
 * It is NOT part of the application. It is never deployed. It shares no code
 * with the production front controller and touches no application state.
 */

declare(strict_types=1);

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

if (str_starts_with($path, '/img/')) {
    $base = realpath(__DIR__ . '/../../public/assets/images');
    $file = realpath($base . '/' . substr($path, 5));

    if ($base === false || $file === false || !str_starts_with($file, $base) || !is_file($file)) {
        http_response_code(404);
        echo 'Not found';
        return true;
    }

    $type = match (strtolower(pathinfo($file, PATHINFO_EXTENSION))) {
        'png' => 'image/png',
        'webp' => 'image/webp',
        default => 'image/jpeg',
    };

    header('Content-Type: ' . $type);
    header('Cache-Control: public, max-age=3600');
    readfile($file);

    return true;
}

// Everything else: let the built-in server handle it as a static file.
return false;
