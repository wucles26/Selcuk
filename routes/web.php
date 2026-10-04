<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Central routes (single domain)
|--------------------------------------------------------------------------
|
| Auth + app UI live in the Filament panel under /app.
| Legacy URLs redirect there so bookmarks keep working.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::redirect('/login', '/app/login')->name('login');
    Route::redirect('/register', '/app/register')->name('register');
});

Route::redirect('/admin', '/app');
Route::redirect('/admin/{path}', '/app/{path}')->where('path', '.*');
