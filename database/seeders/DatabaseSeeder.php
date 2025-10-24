<?php

namespace Database\Seeders;

use App\Enums\HabitCategory;
use App\Models\Habit;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::create([
            'email' => config('app.admin_email'),
            'password' => bcrypt(config('app.admin_password', 'password')),
        ]);

        $faker = Factory::create();

        $user->info()->create([
            'name' => 'Admin',
            'avatar_url' => 'https://api.dicebear.com/9.x/identicon/svg?seed=' . $faker->uuid(),
            'coins' => 999999
        ]);

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
