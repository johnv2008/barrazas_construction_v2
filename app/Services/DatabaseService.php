<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Config;
use App\Core\Logger;
use PDO;
use PDOException;

/**
 * Single shared PDO connection. Uses real prepared statements
 * (emulation disabled), exception mode, and UTF8MB4 throughout.
 */
final class DatabaseService
{
    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection !== null) {
            return self::$connection;
        }

        $host = Config::get('database.host');
        $port = Config::get('database.port');
        $name = Config::get('database.name');
        $charset = Config::get('database.charset', 'utf8mb4');

        // charset= in the DSN is sufficient to set the connection
        // encoding (supported since PHP 5.3.6) — no need for the
        // MYSQL_ATTR_INIT_COMMAND "SET NAMES" workaround, which also
        // avoids a PDO::MYSQL_ATTR_INIT_COMMAND deprecation on newer
        // PHP versions.
        $dsn = "mysql:host={$host};port={$port};dbname={$name};charset={$charset}";

        try {
            self::$connection = new PDO(
                $dsn,
                (string) Config::get('database.user'),
                (string) Config::get('database.password'),
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $e) {
            // Log the real cause internally, but never let a message
            // that may include host/credential details reach the caller.
            Logger::error('Database connection failed', ['driver_message' => $e->getMessage()]);

            throw new PDOException('Database connection failed.', (int) $e->getCode());
        }

        return self::$connection;
    }
}
