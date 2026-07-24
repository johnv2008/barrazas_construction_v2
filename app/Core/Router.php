<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Small array-based router. Routes are registered as
 * [METHOD, path, [ControllerClass, 'action'], middlewareClasses[]].
 * Path parameters use {name} syntax and are passed to the controller
 * action as an associative array.
 */
final class Router
{
    /** @var array<int, array{method: string, path: string, handler: array{0: class-string, 1: string}, middleware: array<int, class-string>}> */
    private array $routes = [];

    public function get(string $path, array $handler, array $middleware = []): void
    {
        $this->add('GET', $path, $handler, $middleware);
    }

    public function post(string $path, array $handler, array $middleware = []): void
    {
        $this->add('POST', $path, $handler, $middleware);
    }

    public function add(string $method, string $path, array $handler, array $middleware = []): void
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler,
            'middleware' => $middleware,
        ];
    }

    public function dispatch(Request $request): void
    {
        $method = $request->method();
        $path = $request->path();
        $pathMatchedDifferentMethod = false;

        foreach ($this->routes as $route) {
            $pattern = $this->toRegex($route['path']);

            if (preg_match($pattern, $path, $matches) !== 1) {
                continue;
            }

            if ($route['method'] !== $method) {
                $pathMatchedDifferentMethod = true;
                continue;
            }

            $params = array_filter(
                $matches,
                static fn ($key) => is_string($key),
                ARRAY_FILTER_USE_KEY
            );

            foreach ($route['middleware'] as $middlewareClass) {
                (new $middlewareClass())->handle($request);
            }

            [$controllerClass, $action] = $route['handler'];
            $controller = new $controllerClass();
            $controller->$action($request, $params);

            return;
        }

        if ($pathMatchedDifferentMethod) {
            Response::abort(404, 'errors/404');
        }

        Response::abort(404, 'errors/404');
    }

    private function toRegex(string $path): string
    {
        $pattern = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<$1>[^/]+)', $path);

        return '#^' . $pattern . '$#';
    }
}
