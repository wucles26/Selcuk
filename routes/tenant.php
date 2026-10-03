<?php

declare(strict_types=1);

use App\Http\Middleware\InitializeTenancyBySession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tenant application routes (single domain, session-based)
|--------------------------------------------------------------------------
|
| Same domain and no /{tenant} prefix. The active tenant comes from the
| session key "tenant_id" (set after login / tenant selection).
|
*/

Route::middleware([
    'web',
    InitializeTenancyBySession::class,
])->prefix('app')->group(function () {
    Route::get('/', function () {
        return response()->json([
            'message' => 'Tenant application',
            'tenant_id' => tenant('id'),
            'database' => DB::connection()->getDatabaseName(),
        ]);
    })->name('tenant.home');
});
