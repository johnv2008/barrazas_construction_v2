<?php

declare(strict_types=1);

/**
 * Admin routes, mounted under the configurable ADMIN_PATH prefix.
 * $router and $adminPath are injected by public/index.php.
 *
 * @var \App\Core\Router $router
 * @var string $adminPath
 */

use App\Controllers\Admin\AuthController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\PlaceholderController;
use App\Middleware\AuthMiddleware;
use App\Middleware\CsrfMiddleware;
use App\Middleware\GuestMiddleware;

$prefix = '/' . trim($adminPath, '/');

$router->get($prefix, [AuthController::class, 'root']);

// Guest-only (login, forgot password)
$router->get($prefix . '/login', [AuthController::class, 'showLogin'], [GuestMiddleware::class]);
$router->post($prefix . '/login', [AuthController::class, 'login'], [GuestMiddleware::class, CsrfMiddleware::class]);
$router->get($prefix . '/forgot-password', [AuthController::class, 'showForgotPassword'], [GuestMiddleware::class]);
$router->post($prefix . '/forgot-password', [AuthController::class, 'forgotPassword'], [GuestMiddleware::class, CsrfMiddleware::class]);

// Authenticated
$router->post($prefix . '/logout', [AuthController::class, 'logout'], [AuthMiddleware::class, CsrfMiddleware::class]);
$router->get($prefix . '/dashboard', [DashboardController::class, 'index'], [AuthMiddleware::class]);

// Placeholder modules (read-only "coming later" screens)
$router->get($prefix . '/pages', [PlaceholderController::class, 'pages'], [AuthMiddleware::class]);
$router->get($prefix . '/services', [PlaceholderController::class, 'services'], [AuthMiddleware::class]);
$router->get($prefix . '/projects', [PlaceholderController::class, 'projects'], [AuthMiddleware::class]);
$router->get($prefix . '/testimonials', [PlaceholderController::class, 'testimonials'], [AuthMiddleware::class]);
$router->get($prefix . '/service-areas', [PlaceholderController::class, 'serviceAreas'], [AuthMiddleware::class]);
$router->get($prefix . '/leads', [PlaceholderController::class, 'leads'], [AuthMiddleware::class]);
$router->get($prefix . '/seo', [PlaceholderController::class, 'seo'], [AuthMiddleware::class]);
$router->get($prefix . '/settings', [PlaceholderController::class, 'settings'], [AuthMiddleware::class]);
$router->get($prefix . '/activity-log', [PlaceholderController::class, 'activityLog'], [AuthMiddleware::class]);
$router->get($prefix . '/administrators', [PlaceholderController::class, 'administrators'], [AuthMiddleware::class]);
