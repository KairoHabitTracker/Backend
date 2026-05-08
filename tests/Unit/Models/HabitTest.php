<?php

use App\Models\Habit;
use App\Models\User;
use App\Enums\HabitCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('builtin scope returns only habits without a user_id', function () {
    $user = User::create([
        'email' => 'builtinscope@example.com',
        'password' => 'pass'
    ]);

    Habit::create([
        'name' => 'Read',
        'emoji' => '📚',
        'hex_color' => '#000000',
        'category' => HabitCategory::LEARNING,
    ]);

    Habit::create([
        'user_id' => $user->id,
        'name' => 'Custom Read',
        'emoji' => '📖',
        'hex_color' => '#111111',
        'category' => HabitCategory::LEARNING,
    ]);

    $builtins = Habit::query()->builtin()->get();

    expect($builtins)->toHaveCount(1)
        ->and($builtins->first()->name)->toBe('Read');
});

test('custom scope returns only habits belonging to the specific user', function () {
    $user1 = User::create([
        'email' => 'customscope1@example.com',
        'password' => 'pass'
    ]);

    $user2 = User::create([
        'email' => 'customscope2@example.com',
        'password' => 'pass'
    ]);

    Habit::create([
        'name' => 'Read',
        'emoji' => '📚',
        'hex_color' => '#000000',
        'category' => HabitCategory::LEARNING,
    ]);

    Habit::create([
        'user_id' => $user1->id,
        'name' => 'User1 Habit',
        'emoji' => '📖',
        'hex_color' => '#111111',
        'category' => HabitCategory::LEARNING,
    ]);

    Habit::create([
        'user_id' => $user2->id,
        'name' => 'User2 Habit',
        'emoji' => '📓',
        'hex_color' => '#222222',
        'category' => HabitCategory::LEARNING,
    ]);

    $user1Habits = Habit::query()->custom($user1->id)->get();

    expect($user1Habits)->toHaveCount(1)
        ->and($user1Habits->first()->name)->toBe('User1 Habit');
});

test('category casts to enum', function () {
    $habit = Habit::create([
        'name' => 'Gym',
        'emoji' => '🏋️',
        'hex_color' => '#ff0000',
        'category' => 'health'
    ]);

    expect($habit->category)->toBeInstanceOf(HabitCategory::class)
        ->and($habit->category)->toBe(HabitCategory::HEALTH);
});

