<?php

use App\Models\User;
use App\Models\Habit;
use App\Models\UserHabit;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('last_completed_at returns null if habit has no completions', function () {
    $user = User::create([
        'email' => 'habit0@example.com',
        'password' => 'password123',
    ]);

    $habitDef = Habit::create([
        'name' => 'Eat Vegetables',
        'emoji' => '🥦',
        'hex_color' => '#00ff00',
        'category' => 'health',
    ]);

    $habit = UserHabit::create([
        'user_id' => $user->id,
        'habit_id' => $habitDef->id,
        'streak' => 0,
        'days_of_week' => ['monday'],
    ]);

    expect($habit->last_completed_at)->toBeNull();
});

test('last_completed_at returns the creation date of the latest completion', function () {
    $user = User::create([
        'email' => 'habit1@example.com',
        'password' => 'password123',
    ]);

    $habitDef = Habit::create([
        'name' => 'Run',
        'emoji' => '🏃',
        'hex_color' => '#0000ff',
        'category' => 'health',
    ]);

    $habit = UserHabit::create([
        'user_id' => $user->id,
        'habit_id' => $habitDef->id,
        'streak' => 1,
        'days_of_week' => ['monday'],
    ]);

    $olderCompletionTime = now()->subDays(2);
    $habit->completions()->create(['created_at' => $olderCompletionTime]);

    $newerCompletionTime = now()->subDay();
    $habit->completions()->create(['created_at' => $newerCompletionTime]);

    expect($habit->fresh()->last_completed_at->toDateTimeString())
        ->toBe($habit->completions()->latest()->first()->created_at->toDateTimeString());
});
