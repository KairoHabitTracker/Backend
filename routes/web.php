<?php

use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/login', [Admin\LoginController::class, 'index'])->name('login');
Route::post('/login', [Admin\LoginController::class, 'store']);
Route::any('/logout', [Admin\LoginController::class, 'destroy']);

Route::group(
    [
        'middleware' => ['auth', 'verified'],
    ],
    function () {
        Route::get('/', function () {
            return Inertia::render('Dashboard');
        })->name('dashboard');
    }
);
