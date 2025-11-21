<?php

namespace Database\Seeders;

use App\Enums\HabitCategory;
use App\Models\Habit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HabitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $habits = [
            [
                'name' => 'Drink Water',
                'emoji' => '💧',
                'hex_color' => '#3498db',
                'category' => HabitCategory::HEALTH,
            ],
            [
                'name' => 'Morning Exercise',
                'emoji' => '🏃‍♂️',
                'hex_color' => '#2ecc71',
                'category' => HabitCategory::PHYSICAL_WELLBEING,
            ],
            [
                'name' => 'Read a Book',
                'emoji' => '📚',
                'hex_color' => '#9b59b6',
                'category' => HabitCategory::LEARNING,
            ],
            [
                'name' => 'Meditate',
                'emoji' => '🧘‍♀️',
                'hex_color' => '#e67e22',
                'category' => HabitCategory::MENTAL_WELLBEING,
            ],
            [
                'name' => 'Save Money',
                'emoji' => '💰',
                'hex_color' => '#f1c40f',
                'category' => HabitCategory::FINANCIAL,
            ]
        ];

        Habit::insert(array_map(function ($habit) {
            return array_merge($habit, [
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }, $habits));
    }
}
