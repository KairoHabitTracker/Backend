<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserInfo;
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
        $user = User::factory()->create([
            'email' => config('app.admin_email'),
            'password' => bcrypt(config('app.admin_password', 'password')),
        ]);

        $faker = Factory::create();

        $user->info()->create([
            'name' => 'Admin',
            'avatar_url' => 'https://api.dicebear.com/9.x/identicon/svg?seed=' . $faker->uuid(),
            'coins' => 999999
        ]);
    }
}
