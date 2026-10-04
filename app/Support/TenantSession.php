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
            self::secureCookies(),
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

    private static function secureCookies(): bool
    {
        if (config('session.secure') !== null) {
            return (bool) config('session.secure');
        }

        return str_starts_with((string) config('app.url'), 'https://');
    }
}
