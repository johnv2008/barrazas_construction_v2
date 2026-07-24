<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

/**
 * Plain-PHP template renderer. Templates live under app/Views and are
 * referenced with dot-free relative paths, e.g. "frontend/home" or
 * "admin/dashboard". No templating DSL — just PHP with escaped output
 * helpers, kept intentionally simple to read.
 */
final class View
{
    private const BASE_PATH = APP_ROOT . '/app/Views/';

    public function render(string $template, array $data = [], ?string $layout = null): void
    {
        echo $this->renderToString($template, $data, $layout);
    }

    public function renderToString(string $template, array $data = [], ?string $layout = null): string
    {
        $content = $this->renderTemplate($template, $data);

        if ($layout !== null) {
            $data['content'] = $content;
            return $this->renderTemplate($layout, $data);
        }

        return $content;
    }

    private function renderTemplate(string $template, array $data): string
    {
        $path = self::BASE_PATH . $template . '.php';

        if (!is_file($path)) {
            throw new RuntimeException("View template not found: {$template}");
        }

        extract($data, EXTR_SKIP);

        ob_start();
        require $path;

        return (string) ob_get_clean();
    }

    /**
     * Render a reusable component/partial from within another template.
     */
    public static function component(string $name, array $data = []): void
    {
        $path = self::BASE_PATH . 'components/' . $name . '.php';

        if (!is_file($path)) {
            throw new RuntimeException("Component not found: {$name}");
        }

        extract($data, EXTR_SKIP);
        require $path;
    }
}
