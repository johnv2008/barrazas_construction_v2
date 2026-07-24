<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Services\SessionService;

final class Csrf
{
    private const SESSION_KEY = '_csrf_token';

    public static function token(): string
    {
        $token = SessionService::get(self::SESSION_KEY);

        if (!is_string($token)) {
            $token = bin2hex(random_bytes(32));
            SessionService::set(self::SESSION_KEY, $token);
        }

        return $token;
    }

    public static function field(): string
    {
        return '<input type="hidden" name="_csrf" value="' . htmlspecialchars(self::token(), ENT_QUOTES, 'UTF-8') . '">';
    }

    public static function verify(?string $submitted): bool
    {
        $token = SessionService::get(self::SESSION_KEY);

        return is_string($token) && is_string($submitted) && hash_equals($token, $submitted);
    }

    /**
     * Rotate the token after a sensitive action (e.g. login) so a
     * leaked or reused token cannot be replayed.
     */
    public static function rotate(): void
    {
        SessionService::set(self::SESSION_KEY, bin2hex(random_bytes(32)));
    }
}
