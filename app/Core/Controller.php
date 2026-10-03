<?php
declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected function view(string $name, array $data = [], int $status = 200, ?string $layout = 'layouts/main'): Response
    {
        return Response::html(View::render($name, $data, $layout), $status);
    }
}
