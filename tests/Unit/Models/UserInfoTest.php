<?php

use App\Models\Habit;
use App\Models\User;
use App\Models\UserHabit;
use App\Models\UserInfo;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('avatar_url returns original url if it starts with http', function () {
    $info = new UserInfo([
        'avatar_url' => 'https://example.com/avatar.png'
    ]);

    expect($info->avatar_url)->toBe('https://example.com/avatar.png');
});

test('avatar_url returns asset url if it does not start with http', function () {
    $info = new UserInfo([
        'avatar_url' => 'my_custom_avatar.png'
    ]);

    expect($info->avatar_url)->toBe(asset('storage/avatars/my_custom_avatar.png'));
});

test('largest streak returns 0 if no habits have completions', function () {
    $user = User::create([
        'email' => 'streak0@example.com',
        'password' => 'password123',
        'email_verified_at' => now(),
    ]);

    expect($user->info->largest_streak)->toBe(0);
});

test('largest streak returns the highest streak among all completed user habits', function () {
    $user = User::create([
        'email' => 'streakmax@example.com',
        'password' => 'password123',
        'email_verified_at' => now(),
    ]);

    $habitDef = Habit::create([
        'name' => 'Drink Water',
        'emoji' => '💧',
        'hex_color' => '#0000ff',
        'category' => 'health',
    ]);

    $habit1 = UserHabit::create([
        'user_id' => $user->id,
        'habit_id' => $habitDef->id,
        'streak' => 5,
        'days_of_week' => ['monday'],
    ]);
    $habit1->completions()->create();

    $habit2 = UserHabit::create([
        'user_id' => $user->id,
        'habit_id' => $habitDef->id,
        'streak' => 12,
        'days_of_week' => ['monday'],
    ]);
    $habit2->completions()->create();

    UserHabit::create([
        'user_id' => $user->id,
        'habit_id' => $habitDef->id,
        'streak' => 15,
        'days_of_week' => ['monday'],
    ]);

    expect($user->info->fresh()->largest_streak)->toBe(12);
});
