<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/login', function () {
    return Inertia::render('Auth/Login');
})->name('login');
Route::group(
    [
        'middleware' => ['auth']
    ],
    function () {
        Route::get('/', function () {
            return Inertia::render('Dashboard');
        })->name('dashboard');
    }
);
