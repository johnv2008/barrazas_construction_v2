<?php

declare(strict_types=1);

namespace App\Core;

use ErrorException;
use PDOException;
use Throwable;

/**
 * Converts PHP errors into exceptions and renders a safe response for
 * any uncaught throwable. In production this never leaks stack
 * traces, file paths, or database credentials — only a generic page
 * plus a log entry. In development the full exception is shown.
 */
final class ErrorHandler
{
    public static function register(): void
    {
        error_reporting(E_ALL);
        ini_set('display_errors', '0');

        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
        register_shutdown_function([self::class, 'handleShutdown']);
    }

    /**
     * Deprecation notices (including ones PHP itself emits after a
     * version upgrade, e.g. for a newly-deprecated constant) are
     * logged but never escalated to a fatal exception — the app
     * should degrade gracefully on a host that upgrades PHP under it,
     * not go down.
     */
    private const NON_FATAL_SEVERITIES = E_DEPRECATED | E_USER_DEPRECATED | E_NOTICE | E_USER_NOTICE | E_STRICT;

    public static function handleError(int $severity, string $message, string $file = '', int $line = 0): bool
    {
        if (!(error_reporting() & $severity)) {
            return false;
        }

        if (($severity & self::NON_FATAL_SEVERITIES) !== 0) {
            Logger::warning($message, ['file' => $file, 'line' => $line, 'severity' => $severity]);

            return true;
        }

        throw new ErrorException($message, 0, $severity, $file, $line);
    }

    public static function handleException(Throwable $e): void
    {
        Logger::exception($e);

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        http_response_code(500);

        $debug = (bool) Config::get('app.debug', false);
        $isDbError = $e instanceof PDOException;

        if ($debug) {
            self::renderDebug($e);
            return;
        }

        $view = $isDbError ? 'errors/db-error' : 'errors/500';

        try {
            (new View())->render($view, [], 'layouts/error');
        } catch (Throwable) {
            echo 'A server error occurred. Please try again shortly.';
        }
    }

    public static function handleShutdown(): void
    {
        $error = error_get_last();

        if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
            self::handleException(new ErrorException(
                $error['message'],
                0,
                $error['type'],
                $error['file'],
                $error['line']
            ));
        }
    }

    private static function renderDebug(Throwable $e): void
    {
        echo '<!doctype html><html><head><meta charset="utf-8"><title>Application Error</title>';
        echo '<style>body{font-family:ui-monospace,Menlo,monospace;background:#17191D;color:#F3F0E9;padding:2rem;}';
        echo 'h1{color:#B99052;} pre{white-space:pre-wrap;background:#22252B;padding:1rem;border-radius:8px;}</style>';
        echo '</head><body>';
        echo '<h1>' . htmlspecialchars($e::class, ENT_QUOTES) . '</h1>';
        echo '<p>' . htmlspecialchars($e->getMessage(), ENT_QUOTES) . '</p>';
        echo '<p>' . htmlspecialchars($e->getFile(), ENT_QUOTES) . ':' . $e->getLine() . '</p>';
        echo '<pre>' . htmlspecialchars($e->getTraceAsString(), ENT_QUOTES) . '</pre>';
        echo '</body></html>';
    }
}
