<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Thin wrapper around PHP superglobals for the current request.
 */
final class Request
{
    private array $query;
    private array $body;
    private array $server;
    private array $cookies;
    private array $files;

    public function __construct()
    {
        $this->query = $_GET;
        $this->body = $_POST;
        $this->server = $_SERVER;
        $this->cookies = $_COOKIE;
        $this->files = $_FILES;
    }

    public function method(): string
    {
        return strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
    }

    public function path(): string
    {
        $uri = $this->server['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';

        if (strlen($path) > 1) {
            $path = rtrim($path, '/');
        }

        return $path === '' ? '/' : $path;
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->body[$key] ?? $this->query[$key] ?? $default;
    }

    public function string(string $key, string $default = ''): string
    {
        $value = $this->input($key, $default);

        return is_string($value) ? trim($value) : $default;
    }

    public function all(): array
    {
        return array_merge($this->query, $this->body);
    }

    public function only(array $keys): array
    {
        return array_intersect_key($this->all(), array_flip($keys));
    }

    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }

    public function isSecure(): bool
    {
        $https = $this->server['HTTPS'] ?? '';
        $proto = $this->server['HTTP_X_FORWARDED_PROTO'] ?? '';

        return (is_string($https) && strtolower($https) !== 'off' && $https !== '')
            || strtolower($proto) === 'https';
    }

    public function ip(): string
    {
        return (string) ($this->server['REMOTE_ADDR'] ?? '0.0.0.0');
    }

    public function userAgent(): string
    {
        return substr((string) ($this->server['HTTP_USER_AGENT'] ?? ''), 0, 255);
    }

    public function header(string $name): ?string
    {
        $key = 'HTTP_' . str_replace('-', '_', strtoupper($name));

        return $this->server[$key] ?? null;
    }
}
