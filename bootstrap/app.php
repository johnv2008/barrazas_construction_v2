<?php

declare(strict_types=1);

use App\Core\Config;
use App\Core\Env;
use App\Core\ErrorHandler;
use App\Core\Request;
use App\Services\SessionService;

/**
 * Application bootstrap. Required by public/index.php and by
 * public/install.php. Wires up autoloading, environment/config,
 * error handling, timezone, and the session — nothing route-specific
 * happens here.
 */

define('APP_ROOT', dirname(__DIR__));

require APP_ROOT . '/bootstrap/autoload.php';
require APP_ROOT . '/app/Helpers/functions.php';

Env::load(APP_ROOT . '/.env');
Config::set(require APP_ROOT . '/app/Config/config.php');

date_default_timezone_set((string) Config::get('app.timezone', 'UTC'));

ErrorHandler::register();

$request = new Request();

SessionService::start($request);

return $request;
