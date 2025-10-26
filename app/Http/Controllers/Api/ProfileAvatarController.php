<?php

namespace App\Http\Controllers\Api;

use Faker\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileAvatarController
{
    /**
     * Update current user's avatar
     */
    public function update(Request $request) {
        $request->validate([
            /**
             * Max size 2MB, allowed types: jpeg, png, jpg, webp
             */
            'avatar' => 'required|image|max:2048|mimes:jpeg,png,jpg,webp',
        ]);

        $user = $request->user();
        $avatarPath = $request->file('avatar')->store('avatars', 'public');

        $user->info->update([
            'avatar_url' => $avatarPath,
        ]);

        return response()->json([
            'message' => 'Avatar updated successfully',
            'avatar_url' => $user->info->avatar_url,
        ]);
    }

    /**
     * Remove current user's avatar
     *
     * Sets avatar to a default placeholder
     */
    public function destroy(Request $request)
    {
        $faker = Factory::create();

        $info = $request->user()->info;

        if (!str_starts_with($info->avatar_url, 'http')) {
            $avatarPath = str_replace(asset('storage/avatars/') . '/', '', $info->avatar_url);
            Storage::disk('public')->delete($avatarPath);
        }

        $info->update([
            'avatar_url' => $faker->uuid(),
        ]);

        return response()->json([
            'message' => 'Avatar removed successfully, set to default',
            'avatar_url' => $info->avatar_url,
        ]);
    }
}
