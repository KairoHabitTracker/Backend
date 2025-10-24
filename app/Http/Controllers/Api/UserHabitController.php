<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserHabitResource;
use App\Rules\DaysOfWeek;
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
            'days_of_week' => ['nullable','array', new DaysOfWeek],
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $userHabit = $request->user()->habits()->create([
            'habit_id' => $request->habit_id,
            'notification_time' => $request->notification_time,
            'days_of_week' => $request->days_of_week,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return new UserHabitResource($userHabit);
    }

    /**
     * Update a user habit
     */
    public function update(Request $request, $id)
    {
        $userHabit = $request->user()->habits()->findOrFail($id);

        $request->validate([
            'notification_time' => 'nullable|date_format:H:i',
            /**
             * @example ["monday", "wednesday", "friday"]
             */
            'days_of_week' => ['nullable','array', new DaysOfWeek],
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $userHabit->update($request->only([
            'notification_time',
            'days_of_week',
            'start_date',
            'end_date',
        ]));

        return new UserHabitResource($userHabit);
    }

    /**
     * Delete a user habit
     */
    public function destroy(Request $request, $id)
    {
        $userHabit = $request->user()->habits()->findOrFail($id);
        $userHabit->delete();

        return response()->json(['message' => 'User habit deleted successfully.']);
    }
}
