<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Services\AuthService;
use App\Services\SessionService;

/**
 * Guards admin routes. Rejects unauthenticated requests. The
 * inactivity timeout itself is enforced in SessionService::start(),
 * which runs on every request before routing.
 */
final class AuthMiddleware
{
    public function handle(Request $request): void
    {
        if (!AuthService::check()) {
            SessionService::flash('error', 'Please sign in to continue.');
            Response::redirect(admin_url('login'));
        }
    }
}
