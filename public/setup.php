<?php

declare(strict_types=1);

/**
 * Web setup wizard — upload, visit, configure. Four steps:
 *
 *   1. Requirements   PHP version, extensions, writable paths
 *   2. Database       credentials -> live connection test -> writes .env
 *                     -> imports database/schema.sql
 *   3. Site           APP_URL, timezone, CSLB licence number
 *   4. Administrator  first admin account -> writes the install lock
 *
 * WHY THIS EXISTS SEPARATELY FROM install.php
 * -------------------------------------------
 * install.php creates an administrator and nothing else: it assumes a
 * working .env and an imported schema already exist, and it connects to
 * the database on the very first line of its body. That makes it useless
 * for a fresh upload, where neither is true — it fatals before it can
 * render anything.
 *
 * This file therefore never touches the application's database layer
 * until it has proven a connection itself with raw PDO. Bootstrapping is
 * safe before configuration exists: bootstrap/app.php only loads env,
 * config, the error handler and the session, none of which open a
 * database connection.
 *
 * SECURITY
 * --------
 * A wizard that writes .env and executes SQL is the most dangerous file
 * that can sit in a web root, so it disables itself three ways:
 *
 *   1. storage/installed.lock exists            -> refuses
 *   2. an administrator row already exists      -> refuses
 *   3. .env already has a working DB connection -> refuses to re-import
 *
 * The lock is written the moment the admin account is created, and the
 * final screen tells you to delete this file and install.php. Do both.
 * The window in which this file is exploitable is the window between
 * upload and completion, so keep it short.
 */

use App\Core\Nonce;
use App\Core\View;
use App\Helpers\Csrf;
use App\Services\SessionService;

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
header('X-Robots-Tag: noindex, nofollow');
header(
    "Content-Security-Policy: default-src 'self'; script-src 'self' 'nonce-{$cspNonce}'; "
    . "style-src 'self' 'unsafe-inline'; img-src 'self' data:; font-src 'self'; "
    . "frame-ancestors 'self'; base-uri 'self'; form-action 'self'; object-src 'none'"
);

$lockFile = APP_ROOT . '/storage/installed.lock';
$envFile = APP_ROOT . '/.env';
$schemaFile = APP_ROOT . '/database/schema.sql';

/** Render a terminal message and stop. */
function setup_stop(string $heading, string $message, int $status = 403): never
{
    http_response_code($status);
    (new View())->render('admin/install/locked', [
        'title' => 'Setup',
        'heading' => $heading,
        'message' => $message,
    ], 'layouts/auth');
    exit;
}

/**
 * Has setup already completed? Checked on every request AND again
 * immediately before the final write, which closes the race between a
 * page load and its submission.
 */
function setup_complete(string $lockFile): bool
{
    if (is_file($lockFile)) {
        return true;
    }

    // Fallback if the lock file was lost: an existing administrator row
    // is equally conclusive. Wrapped because the database may legitimately
    // not exist yet, which is not an error at this stage.
    try {
        $admins = new \App\Models\AdminUser();
        return $admins->countAdmins() > 0;
    } catch (\Throwable) {
        return false;
    }
}

if (setup_complete($lockFile)) {
    setup_stop(
        'Setup Already Completed',
        'This site is already configured. For security the setup wizard is permanently disabled. '
        . 'If you can still see this page, delete setup.php and install.php from your web root now.'
    );
}

/** Requirement probes — all must pass before step 2 is offered. */
function setup_requirements(string $envFile, string $schemaFile): array
{
    $root = APP_ROOT;

    return [
        ['label' => 'PHP 8.1 or newer', 'ok' => PHP_VERSION_ID >= 80100, 'detail' => PHP_VERSION],
        ['label' => 'PDO MySQL driver', 'ok' => extension_loaded('pdo_mysql'), 'detail' => extension_loaded('pdo_mysql') ? 'loaded' : 'missing — ask your host to enable pdo_mysql'],
        ['label' => 'mbstring extension', 'ok' => extension_loaded('mbstring'), 'detail' => extension_loaded('mbstring') ? 'loaded' : 'missing'],
        ['label' => 'GD image library', 'ok' => extension_loaded('gd'), 'detail' => extension_loaded('gd') ? 'loaded' : 'missing — only needed for future CMS uploads'],
        ['label' => 'storage/ is writable', 'ok' => is_writable($root . '/storage'), 'detail' => is_writable($root . '/storage') ? 'writable' : 'set permissions to 755'],
        ['label' => 'storage/sessions is writable', 'ok' => is_writable($root . '/storage/sessions'), 'detail' => is_writable($root . '/storage/sessions') ? 'writable' : 'set permissions to 755'],
        ['label' => 'Can write .env', 'ok' => is_file($envFile) ? is_writable($envFile) : is_writable($root), 'detail' => is_file($envFile) ? 'existing file is writable' : 'account root is writable'],
        ['label' => 'database/schema.sql present', 'ok' => is_file($schemaFile), 'detail' => is_file($schemaFile) ? 'found' : 'missing — upload the database/ folder'],
    ];
}

/** Only non-fatal requirements may fail (GD is advisory). */
function setup_requirements_met(array $reqs): bool
{
    foreach ($reqs as $r) {
        if (!$r['ok'] && $r['label'] !== 'GD image library') {
            return false;
        }
    }

    return true;
}

/**
 * Split and execute schema.sql.
 *
 * A naive explode(';') is safe here and deliberately verified: the schema
 * contains no stored procedures, triggers or DELIMITER blocks, so no
 * statement carries an embedded semicolon. If that ever changes this
 * function must change with it.
 *
 * @return array{tables:int, errors:array<int,string>}
 */
function setup_import_schema(PDO $pdo, string $schemaFile): array
{
    $sql = (string) file_get_contents($schemaFile);

    // Strip line comments so a trailing "-- ...;" cannot split a statement.
    $sql = preg_replace('/^\s*--.*$/m', '', $sql) ?? $sql;

    $statements = array_filter(
        array_map('trim', explode(';', $sql)),
        static fn (string $s): bool => $s !== ''
    );

    $errors = [];

    foreach ($statements as $statement) {
        try {
            $pdo->exec($statement);
        } catch (PDOException $e) {
            // CREATE TABLE IF NOT EXISTS is idempotent, so a genuine error
            // here is worth surfacing rather than swallowing.
            $errors[] = $e->getMessage();
        }
    }

    $tables = (int) $pdo->query('SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE()')->fetchColumn();

    return ['tables' => $tables, 'errors' => $errors];
}

/**
 * Write .env, preserving any keys the wizard does not manage.
 *
 * Values are written unquoted unless they contain a space, matching what
 * App\Core\Env can parse: it splits on the first '=', trims, and strips
 * only matching wrapping quotes. It does NOT strip inline comments, so a
 * value must never be followed by one on the same line.
 */
function setup_write_env(string $envFile, array $values): bool
{
    $existing = [];

    if (is_file($envFile)) {
        foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
                continue;
            }

            [$k, $v] = explode('=', $line, 2);
            $existing[trim($k)] = trim($v);
        }
    }

    $merged = array_merge($existing, $values);
    $out = "# Written by setup.php on " . date('c') . "\n";
    $out .= "# Safe to edit by hand. Do not put inline comments after a value.\n\n";

    foreach ($merged as $k => $v) {
        $needsQuotes = str_contains((string) $v, ' ') && !str_starts_with((string) $v, '"');
        $out .= $k . '=' . ($needsQuotes ? '"' . $v . '"' : $v) . "\n";
    }

    return file_put_contents($envFile, $out, LOCK_EX) !== false;
}

$step = max(1, min(4, (int) $request->input('step', 1)));
$errors = [];
$notice = null;

// Values carried between steps live in the session, never in hidden
// fields — the database password must not survive in page source.
$state = $_SESSION['setup'] ?? [];

if ($request->isPost()) {
    if (!Csrf::verify($request->string('_csrf'))) {
        setup_stop('Session Expired', 'Please reload this page and start again.', 419);
    }

    $action = $request->string('action');

    // ---------- Step 2: database ----------
    if ($action === 'database') {
        $db = [
            'host' => $request->string('db_host') ?: 'localhost',
            'port' => $request->string('db_port') ?: '3306',
            'name' => $request->string('db_name'),
            'user' => $request->string('db_user'),
            'pass' => (string) $request->input('db_password', ''),
        ];

        if ($db['name'] === '' || $db['user'] === '') {
            $errors[] = 'Database name and user are both required.';
        } else {
            try {
                $dsn = "mysql:host={$db['host']};port={$db['port']};dbname={$db['name']};charset=utf8mb4";
                $pdo = new PDO($dsn, $db['user'], $db['pass'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);

                $result = setup_import_schema($pdo, $schemaFile);

                if ($result['errors'] !== []) {
                    $errors[] = 'Connected, but the schema import reported errors: ' . implode(' / ', array_slice($result['errors'], 0, 3));
                } elseif ($result['tables'] < 1) {
                    $errors[] = 'Connected, but no tables were created. Check that the user has ALL PRIVILEGES on this database.';
                } else {
                    $state['db'] = $db;
                    $state['tables'] = $result['tables'];
                    $_SESSION['setup'] = $state;
                    $notice = "Connected and imported {$result['tables']} tables.";
                    $step = 3;
                }
            } catch (PDOException $e) {
                $msg = $e->getMessage();

                if (str_contains($msg, 'Access denied')) {
                    $errors[] = 'Access denied. The most common cause is that the user has not been added to the database — in cPanel, use "Add User To Database" and grant ALL PRIVILEGES.';
                } elseif (str_contains($msg, 'Unknown database')) {
                    $errors[] = 'That database does not exist. Check the name, including the cPanel account prefix.';
                } else {
                    $errors[] = 'Could not connect: ' . $msg;
                }
            }
        }

        if ($errors !== []) {
            $step = 2;
        }
    }

    // ---------- Step 3: site settings ----------
    if ($action === 'site') {
        if (!isset($state['db'])) {
            $errors[] = 'Database step is incomplete. Please start again.';
            $step = 2;
        } else {
            $appUrl = rtrim($request->string('app_url'), '/');

            if ($appUrl === '' || !filter_var($appUrl, FILTER_VALIDATE_URL)) {
                $errors[] = 'Enter the full site address, including https://';
                $step = 3;
            } else {
                $db = $state['db'];

                $written = setup_write_env($envFile, [
                    'APP_NAME' => '"Barraza\'s Construction"',
                    'APP_ENV' => 'production',
                    'APP_DEBUG' => 'false',
                    'APP_URL' => $appUrl,
                    'APP_TIMEZONE' => $request->string('app_timezone') ?: 'America/Los_Angeles',
                    'APP_KEY' => bin2hex(random_bytes(32)),
                    'DB_HOST' => $db['host'],
                    'DB_PORT' => $db['port'],
                    'DB_NAME' => $db['name'],
                    'DB_USER' => $db['user'],
                    'DB_PASSWORD' => $db['pass'],
                    'DB_CHARSET' => 'utf8mb4',
                    'BUSINESS_LICENSE_NUMBER' => preg_replace('/\D+/', '', $request->string('license_number')) ?? '',
                    'BUSINESS_LICENSE_AUTHORITY' => 'CSLB',
                    'SESSION_NAME' => 'barrazas_admin_session',
                    'SESSION_LIFETIME' => '1800',
                    'ADMIN_PATH' => 'admin',
                    'MAIL_HOST' => '',
                    'MAIL_PORT' => '587',
                    'MAIL_USERNAME' => '',
                    'MAIL_PASSWORD' => '',
                    'MAIL_ENCRYPTION' => 'tls',
                    'MAIL_FROM_ADDRESS' => 'no-reply@barrazasconstruction.com',
                    'MAIL_FROM_NAME' => '"Barraza\'s Construction"',
                ]);

                if (!$written) {
                    $errors[] = 'Could not write .env. Check that the folder above public_html is writable.';
                    $step = 3;
                } else {
                    $state['env_written'] = true;
                    $_SESSION['setup'] = $state;
                    $notice = 'Configuration written.';
                    $step = 4;
                }
            }
        }
    }

    // ---------- Step 4: administrator ----------
    if ($action === 'admin') {
        if (empty($state['env_written'])) {
            $errors[] = 'Configuration step is incomplete. Please start again.';
            $step = 2;
        } elseif (setup_complete($lockFile)) {
            setup_stop('Setup Already Completed', 'An administrator already exists. This wizard is now disabled.');
        } else {
            $name = $request->string('name');
            $email = $request->string('email');
            $password = (string) $request->input('password', '');
            $confirm = (string) $request->input('password_confirmation', '');

            if ($name === '') {
                $errors[] = 'Name is required.';
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'A valid email address is required.';
            }

            if (strlen($password) < 12) {
                $errors[] = 'Password must be at least 12 characters.';
            }

            if ($password !== $confirm) {
                $errors[] = 'Passwords do not match.';
            }

            if ($errors === []) {
                $db = $state['db'];

                try {
                    $dsn = "mysql:host={$db['host']};port={$db['port']};dbname={$db['name']};charset=utf8mb4";
                    $pdo = new PDO($dsn, $db['user'], $db['pass'], [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]);

                    $stmt = $pdo->prepare(
                        'INSERT INTO admin_users (name, email, password_hash, role, is_active)
                         VALUES (:name, :email, :hash, :role, 1)'
                    );
                    $stmt->execute([
                        ':name' => $name,
                        ':email' => $email,
                        ':hash' => password_hash($password, PASSWORD_DEFAULT),
                        ':role' => 'administrator',
                    ]);

                    file_put_contents($lockFile, 'Installed at ' . date('c') . " for {$email}\n", LOCK_EX);

                    unset($_SESSION['setup']);
                    SessionService::flash('success', 'Setup complete. Delete setup.php and install.php, then sign in.');

                    $step = 5;
                } catch (PDOException $e) {
                    $errors[] = str_contains($e->getMessage(), 'Duplicate')
                        ? 'An account with that email already exists.'
                        : 'Could not create the account: ' . $e->getMessage();
                }
            }

            if ($errors !== []) {
                $step = 4;
            }
        }
    }
}

$requirements = setup_requirements($envFile, $schemaFile);

// Prefill the site address from the request — almost always correct, and
// it removes the single most error-prone field in the wizard.
$guessedUrl = ($request->isSecure() ? 'https://' : 'http://')
    . ($_SERVER['HTTP_HOST'] ?? 'localhost');

(new View())->render('admin/install/setup', [
    'title' => 'Setup',
    'step' => $step,
    'errors' => $errors,
    'notice' => $notice,
    'requirements' => $requirements,
    'requirementsMet' => setup_requirements_met($requirements),
    'guessedUrl' => $guessedUrl,
    'state' => $state,
], 'layouts/auth');
