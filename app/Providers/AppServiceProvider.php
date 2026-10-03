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
        $this->app->booted(function () {
            if ($this->app->runningInConsole()) {
                return;
            }

            $request = request();
            $host = $request->getHost();

            if ($host !== '') {
                URL::forceRootUrl(rtrim($request->getSchemeAndHttpHost().$request->getBasePath(), '/'));
            }
        });
    }
}
