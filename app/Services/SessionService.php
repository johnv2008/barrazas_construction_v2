<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Config;
use App\Core\Request;

/**
 * Wraps native PHP sessions with hardened cookie settings, file
 * storage under storage/sessions (outside the web root), an
 * inactivity timeout, and helpers used throughout the app.
 */
final class SessionService
{
    private const ACTIVITY_KEY = '_last_activity';

    public static function start(Request $request): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        $savePath = APP_ROOT . '/storage/sessions';

        if (is_dir($savePath)) {
            session_save_path($savePath);
        }

        session_name((string) Config::get('session.name', 'barrazas_session'));

        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'domain' => '',
            'secure' => $request->isSecure(),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        session_start();

        self::enforceInactivityTimeout();
    }

    private static function enforceInactivityTimeout(): void
    {
        $lifetime = (int) Config::get('session.lifetime', 1800);
        $lastActivity = self::get(self::ACTIVITY_KEY);

        if (is_int($lastActivity) && (time() - $lastActivity) > $lifetime) {
            self::destroy();
            session_start();
        }

        self::set(self::ACTIVITY_KEY, time());
    }

    public static function regenerate(): void
    {
        session_regenerate_id(true);
    }

    public static function destroy(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', [
                'expires' => time() - 42000,
                'path' => $params['path'],
                'domain' => $params['domain'],
                'secure' => $params['secure'],
                'httponly' => $params['httponly'],
                'samesite' => $params['samesite'],
            ]);
        }

        session_destroy();
    }

    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function flash(string $key, string $message): void
    {
        $_SESSION['_flash'][$key][] = $message;
    }

    /**
     * Retrieve and clear flash messages for the current request.
     */
    public static function pullFlashes(): array
    {
        $flashes = $_SESSION['_flash'] ?? [];
        unset($_SESSION['_flash']);

        return $flashes;
    }

    /**
     * Store submitted form input so it can be redisplayed once after a
     * validation failure redirect. Never used for password fields.
     */
    public static function setOldInput(array $input): void
    {
        $_SESSION['_old'] = $input;
    }

    public static function pullOldInput(): array
    {
        $old = $_SESSION['_old'] ?? [];
        unset($_SESSION['_old']);

        return $old;
    }
}
