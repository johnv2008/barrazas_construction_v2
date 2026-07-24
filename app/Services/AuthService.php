<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Config;
use App\Core\Request;
use App\Models\AdminUser;
use App\Models\LoginAttempt;

/**
 * Handles the full login lifecycle: per-IP rate limiting, per-account
 * lockout after repeated failures, password verification, session
 * fixation protection, and audit logging. All failure paths return
 * the same generic message so the login form never reveals whether
 * an email address exists.
 */
final class AuthService
{
    public const GENERIC_ERROR = 'Those credentials are not valid, or this account is temporarily locked. Please try again.';

    private AdminUser $adminUsers;
    private LoginAttempt $loginAttempts;

    public function __construct()
    {
        $this->adminUsers = new AdminUser();
        $this->loginAttempts = new LoginAttempt();
    }

    public function attempt(string $email, string $password, Request $request): AuthResult
    {
        $ip = $request->ip();
        $ipWindow = (int) Config::get('security.ip_window_minutes', 15);
        $ipMax = (int) Config::get('security.max_failed_logins_per_ip', 15);

        if ($this->loginAttempts->recentFailuresFromIp($ip, $ipWindow) >= $ipMax) {
            $this->loginAttempts->record($email, $ip, $request->userAgent(), false);

            return AuthResult::failure(self::GENERIC_ERROR);
        }

        $admin = $this->adminUsers->findByEmail($email);

        if ($admin === null || (int) $admin['is_active'] !== 1) {
            $this->loginAttempts->record($email, $ip, $request->userAgent(), false);

            return AuthResult::failure(self::GENERIC_ERROR);
        }

        if ($this->adminUsers->isLocked($admin)) {
            $this->loginAttempts->record($email, $ip, $request->userAgent(), false);

            return AuthResult::failure(self::GENERIC_ERROR);
        }

        if (!password_verify($password, $admin['password_hash'])) {
            $this->adminUsers->recordFailedLogin(
                (int) $admin['id'],
                (int) Config::get('security.max_failed_logins', 5),
                (int) Config::get('security.lockout_minutes', 15)
            );
            $this->loginAttempts->record($email, $ip, $request->userAgent(), false);

            return AuthResult::failure(self::GENERIC_ERROR);
        }

        $this->adminUsers->recordSuccessfulLogin((int) $admin['id']);
        $this->loginAttempts->record($email, $ip, $request->userAgent(), true);

        // Session fixation protection: rotate the session ID on every
        // privilege change.
        SessionService::regenerate();
        SessionService::set('admin_id', (int) $admin['id']);
        SessionService::set('admin_name', $admin['name']);
        SessionService::set('admin_role', $admin['role']);

        ActivityLogService::record((int) $admin['id'], 'login', 'Administrator logged in', $request);

        return AuthResult::success((int) $admin['id']);
    }

    public function logout(Request $request): void
    {
        $adminId = SessionService::get('admin_id');

        if (is_int($adminId)) {
            ActivityLogService::record($adminId, 'logout', 'Administrator logged out', $request);
        }

        SessionService::destroy();
    }

    public static function check(): bool
    {
        return SessionService::get('admin_id') !== null;
    }

    public static function id(): ?int
    {
        $id = SessionService::get('admin_id');

        return is_int($id) ? $id : null;
    }
}
