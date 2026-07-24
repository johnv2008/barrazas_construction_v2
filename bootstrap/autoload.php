<?php

declare(strict_types=1);

/**
 * Minimal PSR-4-style autoloader. No Composer is required to run this
 * application: every class lives under app/ and maps directly onto the
 * App\ namespace, which keeps deployment to shared hosting a plain
 * file upload with no `composer install` step.
 */
spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';

    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relative = substr($class, strlen($prefix));
    $path = APP_ROOT . '/app/' . str_replace('\\', '/', $relative) . '.php';

    if (is_file($path)) {
        require $path;
    }
});
