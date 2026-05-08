<?php

use App\Models\User;
use App\Models\Achievement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

test('user is automatically assigned a ULID upon creation', function () {
    $user = User::create([
        'email' => 'ulid_test@example.com',
        'password' => 'pass'
    ]);

    expect($user->id)->not()->toBeNull()
        ->and(Str::isUlid($user->id->toString()))->toBeTrue();
});

test('user info is automatically created when user is created', function () {
    $user = User::create([
        'email' => 'hello@example.com',
        'password' => 'pass'
    ]);

    expect($user->info)->not()->toBeNull()
        ->and($user->info->name)->toBe('Hello');
});

test('user achievements are automatically populated from existing achievements', function () {
    Achievement::create(['identifier' => 'first_win', 'name' => 'First Blood', 'description' => 'Win once', 'image_url' => 'test.png', 'threshold' => 1]);
    Achievement::create(['identifier' => 'second_win', 'name' => 'Second Blood', 'description' => 'Win twice', 'image_url' => 'test.png', 'threshold' => 2]);

    $user = User::create([
        'email' => 'achieve@example.com',
        'password' => 'pass'
    ]);

    expect($user->achievements()->count())->toBe(2);
});

test('friends attribute returns merged collection of friendsAsUser1 and friendsAsUser2', function () {
    $user1 = User::create(['email' => 'u1@example.com', 'password' => 'pass']);
    $user2 = User::create(['email' => 'u2@example.com', 'password' => 'pass']);
    $user3 = User::create(['email' => 'u3@example.com', 'password' => 'pass']);

    DB::table('friends')->insert([
        ['user1_id' => $user1->id, 'user2_id' => $user2->id, 'created_at' => now(), 'updated_at' => now()],
        ['user1_id' => $user3->id, 'user2_id' => $user1->id, 'created_at' => now(), 'updated_at' => now()],
    ]);

    $user1 = $user1->fresh();

    expect($user1->friends)->toHaveCount(2)
        ->and($user1->friends->pluck('id')->toArray())
        ->toContain($user2->id->toString(), $user3->id->toString());
});
