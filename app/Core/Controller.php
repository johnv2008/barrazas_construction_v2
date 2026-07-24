<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected function view(string $template, array $data = [], ?string $layout = null): void
    {
        (new View())->render($template, $data, $layout);
    }

    protected function redirect(string $to): never
    {
        Response::redirect($to);
    }
}
