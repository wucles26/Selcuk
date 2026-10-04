<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Support\TenantSession;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class InitializeTenancyBySession
{
    /**
     * Resolve the current tenant from the session (single domain, no path/domain id).
     *
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (tenancy()->initialized) {
            return $next($request);
        }

        $tenantId = $request->session()->get('tenant_id')
            ?? $request->cookie(BootstrapTenantFromSession::TENANT_COOKIE);

        if (! is_string($tenantId) || $tenantId === '') {
            Auth::logout();
            TenantSession::forget();

            return redirect()->route('login')->with('error', 'Lütfen giriş yapın.');
        }

        $tenant = Tenant::find($tenantId);

        if (! $tenant) {
            Auth::logout();
            TenantSession::forget();

            return redirect()->route('login')->with('error', 'Seçili organizasyon bulunamadı.');
        }

        tenancy()->initialize($tenant);
        $request->session()->put('tenant_id', $tenant->getTenantKey());

        return $next($request);
    }
}
