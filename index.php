<?php
declare(strict_types=1);

/**
 * Front controller. Tüm HTTP istekleri buradan App\Core\App yönlendiricisine gider.
 * Statik dosyalar (public/) PHP built-in server ve FrankenPHP tarafından doğrudan sunulur.
 */
if (PHP_SAPI === 'cli-server') {
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $file = __DIR__ . $path;
    if ($path !== '/' && is_file($file)) {
        return false;
    }
}

require __DIR__ . '/bootstrap.php';

App\Core\App::boot()->run();
