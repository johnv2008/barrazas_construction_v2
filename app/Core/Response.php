<?php

declare(strict_types=1);

namespace App\Core;

final class Response
{
    public static function redirect(string $to, int $status = 302): never
    {
        header('Location: ' . $to, true, $status);
        exit;
    }

    public static function abort(int $status, string $view): never
    {
        http_response_code($status);
        (new View())->render($view, ['status' => $status], 'layouts/error');
        exit;
    }

    public static function json(array $data, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        exit;
    }
}
