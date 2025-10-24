<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserHabitResource;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserHabitController
{
    /**
     * Get all user habits
     */
    public function index(Request $request)
    {
        return UserHabitResource::collection($request->user()->habits);
    }

    /**
     * Create a new user habit
     */
    public function store(Request $request)
    {
        $request->validate([
            'habit_id' => 'required|exists:habits,id',
            'notification_time' => 'nullable|date_format:H:i',
            /**
             * @example ["monday", "wednesday", "friday"]
             */
            'days_of_week' => 'nullable|array',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($request->has('days_of_week')) {
            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            foreach ($request->days_of_week as $day) {
                if (!in_array(strtolower($day), $days)) {
                    throw ValidationException::withMessages([
                        'days_of_week' => "The day '$day' is not a valid day of the week. Valid days are: " . implode(', ', $days) . ".",
                    ]);
                }
            }
        }

        $userHabit = $request->user()->habits()->create([
            'habit_id' => $request->habit_id,
            'notification_time' => $request->notification_time,
            'days_of_week' => $request->days_of_week,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return new UserHabitResource($userHabit);
    }
}
