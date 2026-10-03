<?php
declare(strict_types=1);

function config(string $key, mixed $default = null): mixed
{
    static $config = null;
    $config ??= require CONFIG_PATH . '/app.php';

    $value = $config;
    foreach (explode('.', $key) as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }
        $value = $value[$segment];
    }

    return $value;
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function asset(string $path): string
{
    return '/public/' . ltrim($path, '/');
}

function url(string $path = '/'): string
{
    $path = '/' . ltrim($path, '/');
    return $path === '//' ? '/' : $path;
}
