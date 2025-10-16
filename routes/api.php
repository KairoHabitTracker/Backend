<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api;

Route::group(
    [
        'middleware' => 'guest:api',
        'prefix' => 'auth',
        'as' => 'auth.'
    ],
    function () {
        Route::post('/register', [Api\RegisterController::class, 'store'])->name('register');
    }
);
