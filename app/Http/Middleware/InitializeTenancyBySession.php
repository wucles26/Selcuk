<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
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
        $tenantId = $request->session()->get('tenant_id');

        if (! is_string($tenantId) || $tenantId === '') {
            Auth::logout();

            return redirect()->route('login')->with('error', 'Lütfen giriş yapın.');
        }

        $tenant = Tenant::find($tenantId);

        if (! $tenant) {
            Auth::logout();
            $request->session()->forget('tenant_id');

            return redirect()->route('login')->with('error', 'Seçili organizasyon bulunamadı.');
        }

        tenancy()->initialize($tenant);

        return $next($request);
    }
}
