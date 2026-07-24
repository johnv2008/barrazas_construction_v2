<?php

declare(strict_types=1);

namespace App\Services;

final class AuthResult
{
    private function __construct(
        public readonly bool $ok,
        public readonly ?string $error,
        public readonly ?int $adminId
    ) {
    }

    public static function success(int $adminId): self
    {
        return new self(true, null, $adminId);
    }

    public static function failure(string $error): self
    {
        return new self(false, $error, null);
    }
}
