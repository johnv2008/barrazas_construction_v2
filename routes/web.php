<?php

declare(strict_types=1);

/**
 * Public-facing routes. $router is injected by public/index.php.
 *
 * @var \App\Core\Router $router
 */

use App\Controllers\HomeController;
use App\Controllers\LeadController;
use App\Controllers\ProjectController;
use App\Controllers\ServiceController;
use App\Middleware\CsrfMiddleware;

$router->get('/', [HomeController::class, 'index']);
$router->get('/sitemap.xml', [HomeController::class, 'sitemap']);
$router->get('/robots.txt', [HomeController::class, 'robots']);

/**
 * Service and project pages resolve by slug against published content
 * only; anything else 404s rather than rendering an empty shell. There is
 * deliberately no route for a service that has no page — an ADU URL must
 * 404, not redirect or show a placeholder (Tier D, DESIGN_SYSTEM.md §8.1).
 *
 * The /services and /projects index routes are intentionally absent until
 * those pages exist. A breadcrumb link to a 404 is worse than a
 * breadcrumb without a link, so the templates render those crumbs
 * unlinked while the index pages are unbuilt.
 */
$router->get('/services/{slug}', [ServiceController::class, 'show']);
$router->get('/projects/{slug}', [ProjectController::class, 'show']);

$router->post('/start-your-project', [LeadController::class, 'store'], [CsrfMiddleware::class]);
