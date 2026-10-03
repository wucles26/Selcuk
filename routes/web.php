<?php

use App\Http\Middleware\InitializeTenancyBySession;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Central routes (single domain)
|--------------------------------------------------------------------------
|
| Tenants are NOT identified by domain or URL path. After a tenant is
| selected (session), /app/* runs against that tenant's database.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/tenancy/enter/{tenant}', function (Request $request, string $tenant) {
    $model = Tenant::find($tenant);

    if (! $model) {
        abort(404, 'Tenant not found.');
    }

    $request->session()->put('tenant_id', $model->getTenantKey());

    return redirect('/app');
})->name('tenancy.enter');

Route::post('/tenancy/leave', function (Request $request) {
    if (tenancy()->initialized) {
        tenancy()->end();
    }

    $request->session()->forget('tenant_id');

    return redirect('/');
})->name('tenancy.leave');
