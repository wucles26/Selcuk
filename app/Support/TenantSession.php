<?php

namespace App\Support;

use App\Http\Middleware\BootstrapTenantFromSession;
use Illuminate\Support\Facades\Cookie;

class TenantSession
{
    public static function remember(string $tenantId): void
    {
        session(['tenant_id' => $tenantId]);

        Cookie::queue(cookie(
            BootstrapTenantFromSession::TENANT_COOKIE,
            $tenantId,
            60 * 24 * 365,
            '/',
            null,
            null,
            true,
            false,
            'lax'
        ));
    }

    public static function forget(): void
    {
        session()->forget('tenant_id');
        Cookie::queue(Cookie::forget(BootstrapTenantFromSession::TENANT_COOKIE));
    }
}
