<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api;

Route::get('/', function (Request $request) {
    return response()->json(['message' => 'API is working']);
});

Route::group(
    [
        'middleware' => 'guest:api',
        'prefix' => 'auth',
        'as' => 'auth.'
    ],
    function () {
        Route::post('/login', [Api\LoginController::class, 'store'])->name('login');
        Route::any('/logout', [Api\LoginController::class, 'destroy'])->name('logout');
        Route::post('/register', [Api\UserController::class, 'store'])->name('register');
    }
);
