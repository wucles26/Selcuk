<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByPath;

/*
|--------------------------------------------------------------------------
| Tenant Routes (single-domain, path-based)
|--------------------------------------------------------------------------
|
| One domain serves everyone. The tenant is selected from the first URL
| segment, e.g. /acme/dashboard → tenant id "acme".
|
*/

Route::middleware([
    'web',
    InitializeTenancyByPath::class,
])->prefix('/{tenant}')->group(function () {
    Route::get('/', function () {
        return response()->json([
            'message' => 'Tenant application',
            'tenant_id' => tenant('id'),
            'database' => DB::connection()->getDatabaseName(),
        ]);
    });
});
