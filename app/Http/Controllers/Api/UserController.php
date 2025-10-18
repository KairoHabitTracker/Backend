<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;

class UserController
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
            'device_name' => ['required'],
        ]);

        $exists = User::query()->where('email', $request->email)->exists();
        if ($exists) {
            return response()->json(['message' => 'Email already exists'], 422);
        }

        $user = User::factory()->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = $user->createToken($request->device_name)->plainTextToken;
        return response()->json(['token' => $token], 201);
    }
}
