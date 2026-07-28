<?php

declare(strict_types=1);

/**
 * Public-facing routes. $router is injected by public/index.php.
 *
 * @var \App\Core\Router $router
 */

use App\Controllers\HomeController;
use App\Controllers\LeadController;
use App\Middleware\CsrfMiddleware;

$router->get('/', [HomeController::class, 'index']);
$router->get('/sitemap.xml', [HomeController::class, 'sitemap']);
$router->get('/robots.txt', [HomeController::class, 'robots']);
$router->post('/start-your-project', [LeadController::class, 'store'], [CsrfMiddleware::class]);
