<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $achievements = [
            [
                'id' => 'first_habit',
                'description' => 'Complete your first habit',
                'goal_value' => 1,
            ],
            [
                'id' => 'habit_streak_7_days',
                'description' => 'Habit Streak: 7 Days',
                'goal_value' => 7,
            ],
            [
                'id' => 'habit_streak_14_days',
                'description' => 'Habit Streak: 14 Days',
                'goal_value' => 14,
            ],
            [
                'id' => 'habit_streak_30_days',
                'description' => 'Habit Streak: 30 Days',
                'goal_value' => 30,
            ],
            [
                'id' => 'habit_streak_60_days',
                'description' => 'Habit Streak: 60 Days',
                'goal_value' => 60,
            ],
            [
                'id' => 'habit_streak_120_days',
                'description' => 'Habit Streak: 120 Days',
                'goal_value' => 120,
            ],
            [
                'id' => 'habit_streak_365_days',
                'description' => 'Habit Streak: 365 Days',
                'goal_value' => 365,
            ],
        ];

        Achievement::insert(array_map(function ($achievement) {
            return array_merge($achievement, [
                'image_url' => Storage::disk('public')->url('achievements/' . $achievement['id'] . '.png'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }, $achievements));
    }
}
