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
        Route::any('/auth/logout-all', [Api\LoginController::class, 'destroyAll'])->name('auth.logoutAll');

        Route::get('/profile', [Api\ProfileController::class,'index'])->name('profile.index');
        Route::put('/profile', [Api\ProfileController::class,'update'])->name('profile.update');

        Route::put('/profile/avatar', [Api\ProfileAvatarController::class,'update'])->name('profile.avatar.update');
        Route::delete('/profile/avatar', [Api\ProfileAvatarController::class,'destroy'])->name('profile.avatar.destroy');

        Route::group(
            [
                'prefix' => 'subscription',
                'as' => 'subscription.'
            ],
            function () {
                Route::get('/', [Api\SubscriptionController::class, 'index'])->name('index');
            }
        );

        Route::group(
            [
                'prefix' => 'habits',
                'as' => 'habits.'
            ],
            function () {
                Route::get('/', [Api\HabitController::class, 'index'])->name('index');

                Route::group(
                    [
                        'prefix' => 'user',
                        'as' => 'user.'
                    ],
                    function () {
                        Route::get('/', [Api\UserHabitController::class, 'index'])->name('index');
                        Route::post('/', [Api\UserHabitController::class, 'store'])->name('store');
                        Route::put('/{id}', [Api\UserHabitController::class, 'update'])->name('update');
                        Route::delete('/{id}', [Api\UserHabitController::class, 'destroy'])->name('destroy');
                    }
                );
            }
        );
    }
);
