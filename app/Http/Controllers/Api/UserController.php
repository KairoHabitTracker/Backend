<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Faker\Factory;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class UserController
{
    /**
     * Register a new user
     *
     * If the environment is local, the user will automatically have their email verified.
     * @unauthenticated
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
            'device_name' => ['required'],
        ]);

        $exists = User::query()->where('email', $request->email)->exists();
        if ($exists) {
            return response()->json(['message' => 'Email already exists'], 422);
        }

        $user = User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $faker = Factory::create();

        $user->info()->create([
            'name' => ucfirst(explode('@', $request->email)[0]),
            'avatar_url' => 'https://api.dicebear.com/9.x/identicon/svg?seed=' . $faker->uuid(),
        ]);

        if(app()->environment('local')) {
            $user->markEmailAsVerified();
        } else {
            event(new Registered($user));
        }

        return response()->json([
            'message' => 'User registered successfully',
        ], 201);
    }
}
