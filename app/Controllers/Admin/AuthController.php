<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Helpers\Csrf;
use App\Models\AdminUser;
use App\Models\PasswordResetToken;
use App\Services\AuthService;
use App\Services\MailService;
use App\Services\SessionService;
use App\Validation\Validator;

final class AuthController extends Controller
{
    public function root(Request $request, array $params): void
    {
        $this->redirect(AuthService::check() ? admin_url('dashboard') : admin_url('login'));
    }

    public function showLogin(Request $request, array $params): void
    {
        $this->view('admin/auth/login', [
            'title' => 'Administrator Sign In',
        ], 'layouts/auth');
    }

    public function login(Request $request, array $params): void
    {
        $email = $request->string('email');
        $password = (string) $request->input('password', '');

        $validator = (new Validator($request->all()))
            ->required('email', 'Email')
            ->email('email', 'Email')
            ->required('password', 'Password');

        if ($validator->fails()) {
            SessionService::setOldInput(['email' => $email]);
            SessionService::flash('error', $validator->firstError() ?? 'Please check the form and try again.');
            $this->redirect(admin_url('login'));
        }

        $result = (new AuthService())->attempt($email, $password, $request);

        if (!$result->ok) {
            // Deliberate small delay to blunt naive brute-force timing.
            usleep(random_int(150_000, 350_000));
            SessionService::setOldInput(['email' => $email]);
            SessionService::flash('error', $result->error ?? AuthService::GENERIC_ERROR);
            $this->redirect(admin_url('login'));
        }

        Csrf::rotate();
        $this->redirect(admin_url('dashboard'));
    }

    public function logout(Request $request, array $params): void
    {
        (new AuthService())->logout($request);
        $this->redirect(admin_url('login'));
    }

    public function showForgotPassword(Request $request, array $params): void
    {
        $this->view('admin/auth/forgot-password', [
            'title' => 'Reset Administrator Password',
        ], 'layouts/auth');
    }

    public function forgotPassword(Request $request, array $params): void
    {
        $email = $request->string('email');

        $validator = (new Validator($request->all()))
            ->required('email', 'Email')
            ->email('email', 'Email');

        if ($validator->fails()) {
            SessionService::flash('error', $validator->firstError() ?? 'Please enter a valid email address.');
            $this->redirect(admin_url('forgot-password'));
        }

        $adminUsers = new AdminUser();
        $admin = $adminUsers->findByEmail($email);

        if ($admin !== null && (int) $admin['is_active'] === 1) {
            $rawToken = bin2hex(random_bytes(32));
            $tokenHash = hash('sha256', $rawToken);

            (new PasswordResetToken())->create((int) $admin['id'], $tokenHash, 60);

            $resetUrl = admin_url('reset-password?token=' . $rawToken);
            MailService::send(
                $email,
                'Reset your Barraza\'s Construction admin password',
                "A password reset was requested for this account. Visit: {$resetUrl}\nThis link expires in 60 minutes."
            );
        }

        // Always show the same message, whether or not the account exists.
        SessionService::flash(
            'success',
            'If that email address is registered, password reset instructions have been sent.'
        );
        $this->redirect(admin_url('login'));
    }
}
