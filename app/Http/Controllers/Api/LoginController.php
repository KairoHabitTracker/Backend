<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController
{
    /**
     * Get a token
     * @unauthenticated
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'device_name' => ['required'],
        ]);

        $user = User::query()->where('email', $request->email)->first();
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
           throw ValidationException::withMessages([
               'email' => ['The provided credentials are incorrect.'],
           ]);
        }

        $token = $user->createToken($credentials['device_name'])->plainTextToken;
        return response()->json(['token' => $token]);
    }

    /**
     * Destroy current token
     */
    public function destroy(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

    /**
     * Destroy all tokens
     */
    public function destroyAll(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'All sessions logged out successfully']);
    }
}
