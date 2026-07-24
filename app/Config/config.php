<?php

declare(strict_types=1);

use App\Core\Env;

/**
 * Central configuration array. Every value is sourced from the
 * environment so no secrets live in version control.
 */
return [
    'app' => [
        'name' => Env::get('APP_NAME', "Barraza's Construction"),
        'env' => Env::get('APP_ENV', 'production'),
        'debug' => Env::bool('APP_DEBUG', false),
        'url' => rtrim((string) Env::get('APP_URL', ''), '/'),
        'timezone' => Env::get('APP_TIMEZONE', 'America/Los_Angeles'),
        'key' => Env::get('APP_KEY', ''),
        'admin_path' => trim((string) Env::get('ADMIN_PATH', 'admin'), '/'),
    ],

    'database' => [
        'host' => Env::get('DB_HOST', '127.0.0.1'),
        'port' => Env::get('DB_PORT', '3306'),
        'name' => Env::get('DB_NAME', ''),
        'user' => Env::get('DB_USER', ''),
        'password' => Env::get('DB_PASSWORD', ''),
        'charset' => Env::get('DB_CHARSET', 'utf8mb4'),
    ],

    'session' => [
        'name' => Env::get('SESSION_NAME', 'barrazas_session'),
        'lifetime' => (int) Env::get('SESSION_LIFETIME', '1800'),
    ],

    'mail' => [
        'host' => Env::get('MAIL_HOST', ''),
        'port' => (int) Env::get('MAIL_PORT', '587'),
        'username' => Env::get('MAIL_USERNAME', ''),
        'password' => Env::get('MAIL_PASSWORD', ''),
        'encryption' => Env::get('MAIL_ENCRYPTION', 'tls'),
        'from_address' => Env::get('MAIL_FROM_ADDRESS', 'no-reply@barrazasconstruction.com'),
        'from_name' => Env::get('MAIL_FROM_NAME', "Barraza's Construction"),
    ],

    'security' => [
        // Failed attempts (per account) before a temporary lockout is applied.
        'max_failed_logins' => 5,
        'lockout_minutes' => 15,
        // Failed attempts from a single IP (any account) within the window below.
        'max_failed_logins_per_ip' => 15,
        'ip_window_minutes' => 15,
    ],
];
