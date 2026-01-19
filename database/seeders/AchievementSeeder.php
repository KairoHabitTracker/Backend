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
                'identifier' => 'first_habit',
                'description' => 'First habit completed',
            ],
            [
                'identifier' => 'complete_a_day',
                'description' => 'Complete all daily habits once',
            ],
            [
                'identifier' => 'habit_streak_7',
                'description' => '7-day habit streak',
            ],
            [
                'identifier' => 'habit_streak_14',
                'description' => '14-day habit streak',
            ],
            [
                'identifier' => 'habit_streak_30',
                'description' => '30-day habit streak',
            ],
            [
                'identifier' => 'habit_streak_90',
                'description' => '90-day habit streak',
            ],
            [
                'identifier' => 'habit_streak_365',
                'description' => '365-day habit streak',
            ],
            [
                'identifier' => 'complete_100_habits',
                'description' => 'Complete 100 habits',
            ],
            [
                'identifier' => 'complete_500_habits',
                'description' => 'Complete 500 habits',
            ],
            [
                'identifier' => 'complete_1000_habits',
                'description' => 'Complete 1000 habits',
            ],
        ];

        Achievement::insert(array_map(function ($achievement) {
            return array_merge($achievement, [
                'image_url' => Storage::disk('public')->url('achievements/' . $achievement['identifier'] . '.png'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }, $achievements));
    }
}
