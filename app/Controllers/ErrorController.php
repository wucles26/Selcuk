<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;

final class ErrorController extends Controller
{
    public function notFound(Request $request): Response
    {
        return $this->view('errors.404', [
            'title' => 'Sayfa bulunamadı',
            'path' => $request->path(),
        ], 404);
    }
}
