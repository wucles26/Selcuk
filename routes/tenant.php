<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\InitializeTenancyBySession;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tenant application routes (single domain, session-based)
|--------------------------------------------------------------------------
|
| Active tenant comes from session key "tenant_id" set at login/register.
|
*/

Route::middleware([
    'web',
    InitializeTenancyBySession::class,
    'auth',
])->prefix('app')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
