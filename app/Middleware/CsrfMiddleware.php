<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Request;
use App\Core\Response;
use App\Helpers\Csrf;
use App\Services\SessionService;

/**
 * Validates the CSRF token on every state-changing request. Applied
 * to POST routes only — GET requests never mutate state in this
 * application.
 */
final class CsrfMiddleware
{
    public function handle(Request $request): void
    {
        if (!$request->isPost()) {
            return;
        }

        if (!Csrf::verify($request->string('_csrf'))) {
            SessionService::flash('error', 'Your session expired. Please try again.');
            Response::abort(403, 'errors/403');
        }
    }
}
