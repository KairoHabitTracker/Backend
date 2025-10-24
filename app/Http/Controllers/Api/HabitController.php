<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\HabitResource;
use App\Models\Habit;
use Illuminate\Http\Request;

class HabitController
{
    /**
     * List default habits.
     */
    public function index(Request $request) {
        return HabitResource::collection(Habit::all());
    }
}
