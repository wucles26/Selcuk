<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Central routes (single domain)
|--------------------------------------------------------------------------
|
| Tenant apps live under /{tenant}/... — see routes/tenant.php.
|
*/

Route::get('/', function () {
    return view('welcome');
});
