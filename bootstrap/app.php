<?php

use App\Http\Middleware\BootstrapTenantFromSession;
use App\Http\Middleware\InitializeTenancyBySession;
use App\Support\TenantSession;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Clear auth when tenant context is missing. Auth::logout() may need the tenant
 * DB for remember-token cycling; fall back to session-only cleanup.
 */
if (! function_exists('flushBrokenAuthSession')) {
    function flushBrokenAuthSession(): void
    {
        try {
            if (Auth::check()) {
                Auth::logout();
            }
        } catch (\Throwable) {
            Auth::forgetGuards();

            foreach (session()->all() as $key => $_) {
                if (is_string($key) && str_starts_with($key, 'login_')) {
                    session()->forget($key);
                }
            }
        }

        TenantSession::forget();
    }
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(
            at: '*',
            headers: Request::HEADER_X_FORWARDED_FOR
                | Request::HEADER_X_FORWARDED_HOST
                | Request::HEADER_X_FORWARDED_PORT
                | Request::HEADER_X_FORWARDED_PROTO
                | Request::HEADER_X_FORWARDED_AWS_ELB,
        );

        $middleware->web(append: [
            BootstrapTenantFromSession::class,
        ]);

        // Auth users live in tenant DBs. Tenancy must boot before Authenticate,
        // otherwise /app short-circuits to /login and loops with guest redirects.
        $middleware->prependToPriorityList(
            before: \Illuminate\Contracts\Auth\Middleware\AuthenticatesRequests::class,
            prepend: BootstrapTenantFromSession::class,
        );
        $middleware->prependToPriorityList(
            before: \Illuminate\Contracts\Auth\Middleware\AuthenticatesRequests::class,
            prepend: InitializeTenancyBySession::class,
        );

        $middleware->redirectGuestsTo(fn () => route('login'));
        $middleware->redirectUsersTo(function () {
            $tenantId = session('tenant_id')
                ?? request()->cookie(BootstrapTenantFromSession::TENANT_COOKIE);

            if (! is_string($tenantId) || $tenantId === '') {
                flushBrokenAuthSession();

                // Send incomplete sessions home (not /login) to avoid guest↔auth loops.
                return '/';
            }

            return '/app';
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
