<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
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
            return redirect('/')->with('error', 'Önce bir tenant seçmeniz gerekiyor.');
        }

        $tenant = Tenant::find($tenantId);

        if (! $tenant) {
            $request->session()->forget('tenant_id');

            return redirect('/')->with('error', 'Seçili tenant bulunamadı.');
        }

        tenancy()->initialize($tenant);

        return $next($request);
    }
}
