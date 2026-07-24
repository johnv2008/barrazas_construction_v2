<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Per-request CSP nonce. Generated once in the front controller and
 * reused by any inline <script> the app legitimately needs (e.g.
 * structured data), so the Content-Security-Policy can keep
 * script-src limited to 'self' plus this one nonce instead of
 * 'unsafe-inline'.
 */
final class Nonce
{
    private static ?string $value = null;

    public static function generate(): string
    {
        self::$value = base64_encode(random_bytes(16));

        return self::$value;
    }

    public static function get(): string
    {
        return self::$value ??= self::generate();
    }
}
