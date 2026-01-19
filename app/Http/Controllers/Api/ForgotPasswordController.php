<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ForgotPasswordController
{
    /**
     * Send a reset link to the given user.
     */
    public function store(Request $request) {
        $request->validate([
            'email' => 'required|email'
        ]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT ?
            response()->json(['message' => __($status)]) :
            response()->json(['message' => __($status)], 400);
    }

    /**
     * Url to open in the app for resetting the password.
     */
    public function show(Request $request, string $token) {
        return response()->json(['message' => 'Open this link in the app', 'token' => $token, 'email' => $request->query('email')]);
    }

    /**
     * Handle an incoming new password request.
     */
    public function update(Request $request, string $token) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation') + ['token' => $token],
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));
            }
        );

        return $status === Password::PasswordReset
            ? response()->json(['message' => __($status)])
            : response()->json(['message' => __($status)], 400);
    }
}
