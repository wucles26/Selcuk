<?php
declare(strict_types=1);

namespace App\Core;

use App\Controllers\ErrorController;

final class App
{
    public function __construct(
        private readonly Router $router,
    ) {
    }

    public static function boot(): self
    {
        $router = new Router();
        $routes = require CONFIG_PATH . '/routes.php';
        $routes($router);

        return new self($router);
    }

    public function run(): void
    {
        $request = Request::capture();
        $match = $this->router->dispatch($request);

        if ($match === null) {
            (new ErrorController())->notFound($request)->send();
            return;
        }

        $handler = $match['handler'];
        $params = $match['params'];

        if (is_array($handler)) {
            [$class, $method] = $handler;
            $controller = new $class();
            $response = $controller->{$method}($request, $params);
        } else {
            $response = $handler($request, $params);
        }

        if (!$response instanceof Response) {
            $response = Response::html((string) $response);
        }

        $response->send();
    }
}
