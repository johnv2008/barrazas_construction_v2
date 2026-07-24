<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Services\AuthService;

/**
 * Keeps already-authenticated administrators away from guest-only
 * pages such as the login screen.
 */
final class GuestMiddleware
{
    public function handle(Request $request): void
    {
        if (AuthService::check()) {
            Response::redirect(admin_url('dashboard'));
        }
    }
}
