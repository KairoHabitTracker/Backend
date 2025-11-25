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
        return UserHabitResource::collection($request->user()->habits());
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
//
//    /**
//     * Complete a habit
//     */
//    public function complete(Request $request, $id)
//    {
//        $userHabit = $request->user()->habits()->findOrFail($id);
//        if($userHabit->end_date && now()->greaterThan($userHabit->end_date)) {
//            throw ValidationException::withMessages(['end_date' => 'Habit has already ended.']);
//        }
//
//        if($userHabit->last_completed_at && $userHabit->last_completed_at->isToday()) {
//            throw ValidationException::withMessages(['last_completed_at' => 'Habit already completed today.']);
//        }
//
//        $todayDayOfWeek = strtolower(now()->format('l'));
//        $daysOfWeek = $userHabit->days_of_week;
//        if (!in_array($todayDayOfWeek, $daysOfWeek)) {
//            throw ValidationException::withMessages(['days_of_week' => 'Habit not scheduled for today.']);
//        }
//
//        $lastCompletedAt = $userHabit->last_completed_at;
//        if($lastCompletedAt && $lastCompletedAt->isAfter(now()->subWeek()->subDay())) {
//            if(count($daysOfWeek) > 1) {
//                // Check if there was a scheduled day between last_completed_at and today
//                $nextScheduledDay = null;
//                foreach ($daysOfWeek as $day) {
//                    $dayIndex = array_search($day, ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
//                    $lastCompletedDayIndex = $lastCompletedAt->dayOfWeek;
//                    $daysUntilNext = ($dayIndex - $lastCompletedDayIndex + 7) % 7;
//                    if ($daysUntilNext > 0 && $daysUntilNext <= 7) {
//                        $scheduledDate = $lastCompletedAt->copy()->addDays($daysUntilNext);
//                        if ($scheduledDate->isBefore(now()) || $scheduledDate->isToday()) {
//                            $nextScheduledDay = $scheduledDate;
//                            break;
//                        }
//                    }
//                }
//                if (is_null($nextScheduledDay) || $nextScheduledDay->isBefore(now()->startOfDay())) {
//                    $userHabit->streak = 1;
//                } else {
//                    $userHabit->streak += 1;
//                }
//            } else {
//                $userHabit->streak = 1;
//            }
//        }
//
//        $userHabit->last_completed_at = now();
//        $userHabit->save();
//    }
}
