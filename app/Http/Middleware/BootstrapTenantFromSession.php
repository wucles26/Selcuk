<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Support\TenantSession;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BootstrapTenantFromSession
{
    public const TENANT_COOKIE = 'tenant_id';

    /**
     * Initialize tenancy whenever a tenant id is present in session or cookie.
     *
     * Central pages need this so Auth/@auth/guest can resolve users that live
     * in the tenant database (including remember-me recall).
     *
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (tenancy()->initialized) {
            return $next($request);
        }

        $tenantId = $request->session()->get('tenant_id')
            ?? $request->cookie(self::TENANT_COOKIE);

        if (! is_string($tenantId) || $tenantId === '') {
            return $next($request);
        }

        $tenant = Tenant::find($tenantId);

        if (! $tenant) {
            TenantSession::forget();

            $response = $next($request);
            $response->headers->clearCookie(self::TENANT_COOKIE);

            return $response;
        }

        try {
            tenancy()->initialize($tenant);
        } catch (\Throwable $exception) {
            report($exception);
            TenantSession::forget();

            $response = $next($request);
            $response->headers->clearCookie(self::TENANT_COOKIE);

            return $response;
        }

        $request->session()->put('tenant_id', $tenant->getTenantKey());

        return $next($request);
    }
}
