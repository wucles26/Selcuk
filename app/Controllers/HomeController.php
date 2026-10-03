<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Models\Site;

final class HomeController extends Controller
{
    public function index(Request $request, array $params): Response
    {
        $site = new Site();

        return $this->view('home.index', [
            'title' => $site->name(),
            'phpVersion' => PHP_VERSION,
        ]);
    }

    public function about(Request $request, array $params): Response
    {
        return $this->view('home.about', [
            'title' => 'Hakkında',
        ]);
    }
}
