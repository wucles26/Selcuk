<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $appUrl = (string) config('app.url');

        // Railway (and most PaaS) terminate TLS before PHP. Prefer APP_URL scheme,
        // then X-Forwarded-Proto, so redirects never bounce http↔https.
        if (str_starts_with($appUrl, 'https://') || $this->forwardedHttps()) {
            URL::forceScheme('https');
        }

        $this->app->booted(function () use ($appUrl) {
            if ($this->app->runningInConsole()) {
                return;
            }

            $request = request();
            $host = $request->getHost();

            if ($host === '') {
                return;
            }

            $root = rtrim($request->getSchemeAndHttpHost().$request->getBasePath(), '/');

            if (str_starts_with($appUrl, 'https://')) {
                $root = preg_replace('#^http://#', 'https://', $root) ?? $root;
            }

            URL::forceRootUrl($root);
        });
    }

    private function forwardedHttps(): bool
    {
        $proto = (string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '');

        return str_contains(strtolower($proto), 'https');
    }
}
