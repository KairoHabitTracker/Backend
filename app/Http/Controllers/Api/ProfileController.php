<?php

namespace App\Http\Controllers\Api;

use App\Enums\HabitCategory;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

    /**
     * Onboard the user
     */
    public function onboard(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:0',
            'interests' => 'required|array',
            'interests.*' => [
                Rule::enum(HabitCategory::class),
                'distinct'
            ]
        ]);

        $user = $request->user();

        $user->info()->update([
            'name' => $request->name,
            'age' => $request->age,
            'onboarded_at' => now(),
        ]);

        $user->info->interests()->delete();
        foreach ($request->interests as $interest) {
            $user->info->interests()->create(['interest' => $interest]);
        }

        return UserResource::make($user->fresh());
    }
}
