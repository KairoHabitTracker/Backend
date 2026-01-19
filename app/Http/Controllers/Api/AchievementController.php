<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\AchievementResource;
use App\Http\Resources\UserAchievementResource;
use App\Models\Achievement;
use Illuminate\Http\Request;

class AchievementController
{
    /**
     *  Get the authenticated user's achievements
     */
    public function index(Request $request)
    {
        return UserAchievementResource::collection($request->user()->achievements);
    }
}
