<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\UserHabitResource;
use App\Models\UserAchievement;
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
        return UserHabitResource::collection($request->user()->habits()->get());
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
            'days_of_week' => ['nullable', 'array', new DaysOfWeek],
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
    public function update(Request $request, int $id)
    {
        $userHabit = $request->user()->habits()->findOrFail($id);

        $request->validate([
            'notification_time' => 'nullable|date_format:H:i',
            /**
             * @example ["monday", "wednesday", "friday"]
             */
            'days_of_week' => ['nullable', 'array', new DaysOfWeek],
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
    public function destroy(Request $request, int $id)
    {
        $userHabit = $request->user()->habits()->findOrFail($id);
        $userHabit->delete();

        return response()->json(['message' => 'User habit deleted successfully.']);
    }

    /**
     * Complete a habit
     */
    public function complete(Request $request, int $id) // I'm going insane
    {
        $user = $request->user();
        $userHabit = $user->habits()->findOrFail($id);
        if ($userHabit->end_date && now()->greaterThan($userHabit->end_date)) {
            throw ValidationException::withMessages(['end_date' => 'Habit has already ended.']);
        }

        $addedToStreak = false;
        $lastCompletion = $userHabit->completions()->latest()->first();
        if ($lastCompletion) {
            if ($lastCompletion->created_at->isToday()) {
                throw ValidationException::withMessages(['last_completed_at' => 'Habit already completed today.']);
            }

            // Check if the last completion was on the specified last day of the week before today to add to the streak
            $daysOfWeek = $userHabit->days_of_week; // ex. ['monday', 'wednesday', 'friday']
            $todayDayOfWeek = strtolower(now()->englishDayOfWeek);
            if (count($daysOfWeek) == 1) { // this means the habit is set to repeat weekly
                // check if the last completion was exactly one week ago
                if ($lastCompletion->created_at->diffInDays(now()) == 7 && $daysOfWeek[0] == $todayDayOfWeek) {
                    $userHabit->streak += 1;
                    $addedToStreak = true;
                } else {
                    $userHabit->streak = 1;
                }
            } else if (count($daysOfWeek) == 7) { // this means the habit is set to repeat daily
                // check if the last completion was exactly one day ago
                if ($lastCompletion->created_at->diffInDays(now()) == 1) {
                    $userHabit->streak += 1;
                    $addedToStreak = true;
                } else {
                    $userHabit->streak = 1;
                }
            } else { // this means the habit is set to repeat on different days of the week
                // check if the last completion was on the day before today in the $daysOfWeek array
                $lastDayIndex = array_search($todayDayOfWeek, $daysOfWeek) - 1;
                if ($lastDayIndex < 0) {
                    $lastDayIndex = count($daysOfWeek) - 1;
                }
                $lastDayOfWeek = $daysOfWeek[$lastDayIndex];
                $lastDayDate = now()->copy()->previous($lastDayOfWeek);
                if ($lastCompletion->created_at->isSameDay($lastDayDate)) {
                    $userHabit->streak += 1;
                    $addedToStreak = true;
                } else {
                    $userHabit->streak = 1;
                }
            }
        } else {
            $userHabit->streak = 1;
            UserAchievement::unlock('first_habit', $user);
        }

        if ($addedToStreak) {
            $stages = [7, 14, 30, 90, 365];
            if(in_array($userHabit->streak, $stages)) {
                UserAchievement::unlock('habit_streak_' . $userHabit->streak, $user);
            }
        }

        $userHabit->completions()->create();
        $userHabit->save();

        $userCompletions = $user->completions()->count();
        $stages = [3, 20, 50, 100, 500, 1000];
        if(in_array($userCompletions, $stages)) {
            UserAchievement::unlock('complete_' . $userCompletions . '_habits', $user);
        }

        $today = now();

        $habits = $user->habits()->get();
        $dueHabits = $habits->filter(function ($h) use ($today) {
            if ($h->end_date && $today->greaterThan($h->end_date)) {
                return false;
            }
            if ($h->start_date && $today->lessThan($h->start_date)) {
                return false;
            }

            $days = $h->days_of_week;
            if (empty($days) || !is_array($days)) {
                return true;
            }

            $todayDay = strtolower($today->englishDayOfWeek);
            $daysLower = array_map('strtolower', $days);

            return in_array($todayDay, $daysLower);
        });

        if ($dueHabits->count() > 0) {
            $allCompleted = $dueHabits->every(function ($h) use ($today) {
                return $h->completions()->whereDate('created_at', $today->toDateString())->exists();
            });

            if ($allCompleted) {
                UserAchievement::unlock('complete_a_day', $user);
            }
        }

        return new UserHabitResource($userHabit);
    }

    /**
     * Undo a habit completion
     */
    public function uncomplete(Request $request, int $id)
    {
        $userHabit = $request->user()->habits()->findOrFail($id);
        $lastCompletion = $userHabit->completions()->latest()->first();
        if (!$lastCompletion || !$lastCompletion->created_at->isToday()) {
            throw ValidationException::withMessages(['last_completed_at' => 'No completion found for today to undo.']);
        }

        $lastCompletion->delete();

        $userHabit->streak = max(0, $userHabit->streak - 1);
        $userHabit->save();
    }

    /**
     * Get all completions for given habit
     */
    public function completions(Request $request, int $id)
    {
        $userHabit = $request->user()->habits()->findOrFail($id);
        return UserHabitResource::collection($userHabit->completions()->get());
    }
}
