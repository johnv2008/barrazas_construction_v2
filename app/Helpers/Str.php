<?php

declare(strict_types=1);

namespace App\Helpers;

final class Str
{
    public static function slug(string $value): string
    {
        $value = strtolower(trim($value));
        $value = preg_replace('/[^a-z0-9]+/', '-', $value) ?? '';

        return trim($value, '-');
    }

    public static function limit(string $value, int $length = 150, string $suffix = '...'): string
    {
        if (mb_strlen($value) <= $length) {
            return $value;
        }

        return rtrim(mb_substr($value, 0, $length)) . $suffix;
    }
}
