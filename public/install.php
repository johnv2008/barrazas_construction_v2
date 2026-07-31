<?php

declare(strict_types=1);

/**
 * One-time installer: creates the first administrator account, then
 * locks itself out permanently. Safe to leave uploaded temporarily,
 * but DELETE THIS FILE after installation completes (see README).
 *
 * Lock mechanism is two-layered:
 *   1. storage/installed.lock (outside the web root — cannot be
 *      removed by any web request) is checked first.
 *   2. A row already existing in admin_users is checked as a fallback,
 *      in case the lock file was somehow lost.
 * Either condition alone is sufficient to permanently disable this
 * script for the remainder of the request AND all future requests.
 */

use App\Core\Nonce;
use App\Core\View;
use App\Helpers\Csrf;
use App\Models\AdminUser;
use App\Services\ActivityLogService;
use App\Services\SessionService;
use App\Validation\Validator;

/**
 * Locate bootstrap/ — it may sit ABOVE the web root or INSIDE it.
 *
 * The preferred layout keeps app/, bootstrap/, database/, routes/ and
 * storage/ one level above the document root, where the web server cannot
 * reach them at all. Some shared hosts (one.com among them) do not let you
 * upload above the web root through their File Manager, so that layout is
 * simply unavailable to those accounts.
 *
 * Rather than make the person deploying fight their control panel, both
 * layouts are supported: prefer the sibling location, fall back to the
 * in-root one. APP_ROOT itself needs no special case — bootstrap/ is always
 * one level beneath it, so dirname(__DIR__) inside bootstrap/app.php
 * resolves correctly either way.
 *
 * The in-root layout is safe because every one of those folders ships a
 * `Require all denied` .htaccess, and public/.htaccess additionally refuses
 * to serve dotfiles and .env/.sql/.log/.md by extension.
 */
$appRoot = is_file(dirname(__DIR__) . '/bootstrap/app.php') ? dirname(__DIR__) : __DIR__;
$request = require $appRoot . '/bootstrap/app.php';

$cspNonce = Nonce::get();
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');
header(
    "Content-Security-Policy: default-src 'self'; script-src 'self' 'nonce-{$cspNonce}'; "
    . "style-src 'self' 'unsafe-inline'; img-src 'self' data:; font-src 'self'; "
    . "frame-ancestors 'self'; base-uri 'self'; form-action 'self'; object-src 'none'"
);

$lockFile = APP_ROOT . '/storage/installed.lock';
$adminUsers = new AdminUser();

function installer_locked(string $lockFile, AdminUser $adminUsers): bool
{
    return is_file($lockFile) || $adminUsers->countAdmins() > 0;
}

function render_installer_message(string $heading, string $message, int $status = 403): never
{
    http_response_code($status);
    (new View())->render('admin/install/locked', [
        'title' => 'Installation',
        'heading' => $heading,
        'message' => $message,
    ], 'layouts/auth');
    exit;
}

if (installer_locked($lockFile, $adminUsers)) {
    render_installer_message(
        'Installation Already Completed',
        'An administrator account already exists. For security, this installer is now permanently disabled. If you need to create additional administrators, sign in and use the Administrators section, or run the SQL steps in README.md.'
    );
}

$errors = [];
$name = '';
$email = '';

if ($request->isPost()) {
    if (!Csrf::verify($request->string('_csrf'))) {
        render_installer_message('Session Expired', 'Please reload this page and try again.', 419);
    }

    $name = $request->string('name');
    $email = $request->string('email');
    $password = (string) $request->input('password', '');
    $passwordConfirmation = (string) $request->input('password_confirmation', '');

    $validator = (new Validator($request->all()))
        ->required('name', 'Name')
        ->required('email', 'Email')
        ->email('email', 'Email')
        ->required('password', 'Password')
        ->minLength('password', 12, 'Password')
        ->matches('password_confirmation', 'password', 'Password confirmation');

    if ($validator->fails()) {
        $errors = $validator->errors();
    } elseif (installer_locked($lockFile, $adminUsers)) {
        // Re-check immediately before writing, closing the race window
        // between the page load above and this submission.
        render_installer_message(
            'Installation Already Completed',
            'An administrator account already exists. This installer is now permanently disabled.'
        );
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $adminId = $adminUsers->create($name, $email, $hash, 'administrator');

        file_put_contents(
            $lockFile,
            "Installed at " . date('c') . " for {$email}\n",
            LOCK_EX
        );

        ActivityLogService::record($adminId, 'install', 'Initial administrator account created', $request);

        SessionService::flash('success', 'Administrator account created. Please sign in — and delete public/install.php now.');
        header('Location: ' . admin_url('login'));
        exit;
    }
}

(new View())->render('admin/install/form', [
    'title' => 'Install Barraza\'s Construction Admin',
    'errors' => $errors,
    'name' => $name,
    'email' => $email,
], 'layouts/auth');
