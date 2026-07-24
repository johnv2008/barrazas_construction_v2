<?php

declare(strict_types=1);

namespace App\Core;

use Throwable;

/**
 * Flat-file application logger. Writes one file per day under
 * storage/logs/. This is intentionally simple — it exists to give
 * developers/operators visibility into runtime errors without ever
 * printing sensitive detail to the browser in production.
 */
final class Logger
{
    private static function path(): string
    {
        return APP_ROOT . '/storage/logs/app-' . date('Y-m-d') . '.log';
    }

    public static function log(string $level, string $message, array $context = []): void
    {
        $line = sprintf(
            '[%s] %s: %s %s%s',
            date('Y-m-d H:i:s'),
            strtoupper($level),
            $message,
            $context !== [] ? json_encode($context, JSON_UNESCAPED_SLASHES) : '',
            PHP_EOL
        );

        $dir = dirname(self::path());

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        error_log($line, 3, self::path());
    }

    public static function info(string $message, array $context = []): void
    {
        self::log('info', $message, $context);
    }

    public static function warning(string $message, array $context = []): void
    {
        self::log('warning', $message, $context);
    }

    public static function error(string $message, array $context = []): void
    {
        self::log('error', $message, $context);
    }

    public static function exception(Throwable $e): void
    {
        self::error($e->getMessage(), [
            'exception' => $e::class,
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);
    }
}
