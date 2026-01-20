<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class ProfileController
{
    /**
     * Get current user
     */
    public function index(Request $request): UserResource {
        return UserResource::make($request->user());
    }

    /**
     * Update current user
     */
    public function update(Request $request): UserResource {
        $request->validate([
            'name' => 'sometimes|string|max:255',
        ]);

        $request->user()->info->update($request->only('name'));

        return UserResource::make($request->user());
    }
}
