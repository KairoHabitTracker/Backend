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
        Route::post('/register', [Api\UserController::class, 'store'])->name('register');
    }
);

Route::group(
    [
        'middleware' => 'auth:api',

    ],
    function () {
        Route::any('/auth/logout', [Api\LoginController::class, 'destroy'])->name('auth.logout');

        Route::get('/profile', [Api\ProfileController::class,'index'])->name('profile.index');
    }
);
