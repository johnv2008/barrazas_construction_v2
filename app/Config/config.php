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

    /**
     * Public business facts surfaced sitewide. Not secrets — these are
     * published on every page by design. California B&P Code 7030.5
     * requires the CSLB license number in all advertising, this site
     * included, so `license_number` must be set before launch. Every
     * display site is conditional on it, so a blank value degrades
     * quietly rather than rendering "License #".
     *
     * Phase 2 moves these to the `site_settings` table (see
     * database/seed.sql) and reads them through a settings service;
     * until that exists, config is the single source of truth.
     */
    'business' => [
        'license_number' => Env::get('BUSINESS_LICENSE_NUMBER', ''),
        'license_authority' => Env::get('BUSINESS_LICENSE_AUTHORITY', 'CSLB'),
    ],

    /**
     * Google Analytics 4.
     *
     * Deliberately config-driven rather than pasted into the layout:
     *   - it stays out of version control, so a fork or a staging copy does
     *     not silently report into the live property;
     *   - it can be turned off by clearing one value, with no code edit;
     *   - and the tag only renders in production (see components/analytics),
     *     so local development never pollutes the data.
     *
     * Adding it widens the CSP — see the note in public/index.php.
     */
    'analytics' => [
        'ga_measurement_id' => Env::get('GA_MEASUREMENT_ID', ''),
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
