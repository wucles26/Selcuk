<?php
declare(strict_types=1);

namespace App\Core;

final class Router
{
    /** @var list<array{method:string,pattern:string,handler:array|callable}> */
    private array $routes = [];

    public function get(string $path, array|callable $handler): self
    {
        return $this->add('GET', $path, $handler);
    }

    public function post(string $path, array|callable $handler): self
    {
        return $this->add('POST', $path, $handler);
    }

    public function add(string $method, string $path, array|callable $handler): self
    {
        $path = '/' . trim($path, '/');
        $this->routes[] = [
            'method' => strtoupper($method),
            'pattern' => $path === '//' ? '/' : $path,
            'handler' => $handler,
        ];

        return $this;
    }

    public function dispatch(Request $request): ?array
    {
        foreach ($this->routes as $route) {
            if ($route['method'] !== $request->method()) {
                continue;
            }

            $params = $this->match($route['pattern'], $request->path());
            if ($params === null) {
                continue;
            }

            return [
                'handler' => $route['handler'],
                'params' => $params,
            ];
        }

        return null;
    }

    private function match(string $pattern, string $path): ?array
    {
        $patternParts = explode('/', trim($pattern, '/'));
        $pathParts = explode('/', trim($path, '/'));

        if ($pattern === '/' && $path === '/') {
            return [];
        }

        if (count($patternParts) !== count($pathParts)) {
            return null;
        }

        $params = [];
        foreach ($patternParts as $index => $part) {
            if (preg_match('/^\{([a-zA-Z_][a-zA-Z0-9_]*)\}$/', $part, $matches) === 1) {
                $params[$matches[1]] = $pathParts[$index];
                continue;
            }

            if ($part !== $pathParts[$index]) {
                return null;
            }
        }

        return $params;
    }
}
