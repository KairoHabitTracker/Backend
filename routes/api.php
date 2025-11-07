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
        Route::group(
            [
                'prefix' => 'email',
                'as' => 'verification.'
            ],
            function () {
                Route::get('/verify', [Api\EmailVerificationController::class, 'index'])
                    ->name('notice');

                Route::get('/verify/{id}/{hash}', [Api\EmailVerificationController::class, 'verify'])
                    ->name('verify')->middleware('signed');

                Route::post('/verification-notification', [Api\EmailVerificationController::class, 'send'])
                    ->name('send')->middleware('throttle:6,1');
            }
        );

        Route::any('/auth/logout', [Api\LoginController::class, 'destroy'])->name('auth.logout');
        Route::any('/auth/logout-all', [Api\LoginController::class, 'destroyAll'])->name('auth.logoutAll');

        Route::group(
            [
                'middleware' => 'verified'
            ],
            function () {
                Route::get('/profile', [Api\ProfileController::class,'index'])->name('profile.index');
                Route::put('/profile', [Api\ProfileController::class,'update'])->name('profile.update');

                Route::put('/profile/avatar', [Api\ProfileAvatarController::class,'update'])->name('profile.avatar.update');
                Route::delete('/profile/avatar', [Api\ProfileAvatarController::class,'destroy'])->name('profile.avatar.destroy');

                Route::group(
                    [
                        'prefix' => 'friend-requests',
                        'as' => 'friendRequests.'
                    ],
                    function () {
                        Route::get('/received', [Api\FriendRequestController::class, 'received'])->name('received');
                        Route::get('/sent', [Api\FriendRequestController::class, 'sent'])->name('sent');
                        Route::post('/', [Api\FriendRequestController::class, 'store'])->name('store');
                        Route::post('/accept/{id}', [Api\FriendRequestController::class, 'accept'])->name('accept');
                        Route::post('/reject/{id}', [Api\FriendRequestController::class, 'reject'])->name('reject');
                        Route::delete('/{id}', [Api\FriendRequestController::class, 'destroy'])->name('destroy');
                    }
                );

                Route::group(
                    [
                        'prefix' => 'friends',
                        'as' => 'friends.'
                    ],
                    function () {
                        Route::get('/', [Api\FriendController::class, 'index'])->name('index');
                        Route::delete('/{id}', [Api\FriendController::class, 'destroy'])->name('destroy');
                    }
                );

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
                        Route::get('/custom', [Api\HabitController::class, 'custom'])->name('custom');
                        Route::post('/custom', [Api\HabitController::class, 'store'])->name('store');
                        Route::put('/custom/{id}', [Api\HabitController::class, 'update'])->name('update');
                        Route::delete('/custom/{id}', [Api\HabitController::class, 'destroy'])->name('destroy');

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
    }
);
