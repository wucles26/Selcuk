<?php
declare(strict_types=1);

namespace App\Core;

final class View
{
    public static function render(string $view, array $data = [], ?string $layout = 'layouts/main'): string
    {
        $viewFile = VIEW_PATH . '/' . str_replace('.', '/', $view) . '.php';
        if (!is_file($viewFile)) {
            throw new \RuntimeException('View not found: ' . $view);
        }

        $content = self::capture($viewFile, $data);

        if ($layout === null) {
            return $content;
        }

        $layoutFile = VIEW_PATH . '/' . str_replace('.', '/', $layout) . '.php';
        if (!is_file($layoutFile)) {
            throw new \RuntimeException('Layout not found: ' . $layout);
        }

        return self::capture($layoutFile, array_merge($data, ['content' => $content]));
    }

    private static function capture(string $file, array $data): string
    {
        extract($data, EXTR_SKIP);
        ob_start();
        require $file;
        return (string) ob_get_clean();
    }
}
