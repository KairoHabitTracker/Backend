<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Habit;

pest()->use(RefreshDatabase::class);

function authHeadersUserHabits(): array {
    $user = User::create([
        'email' => 'userhabits@example.com',
        'password' => 'password12345',
    ]);

    $user->info()->create([
        'name' => 'User Habits',
        'avatar_url' => 'https://example.com/a.svg',
    ]);

    $token = $user->createToken('tests')->plainTextToken;

    return [
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json',
    ];
}

function getAuthUser(): User {
    return User::where('email', 'userhabits@example.com')->firstOrFail();
}

test('List user habits', function () {
    $headers = authHeadersUserHabits();

    $response = $this->get('/api/habits/user', $headers);

    $response->assertOk();
    $response->assertJsonStructure(['data']);
});

test('Create, update and delete a user habit', function () {
    $headers = authHeadersUserHabits();

    $this->seed();
    $habit = Habit::first();

    $create = $this->post('/api/habits/user', [
        'habit_id' => $habit->id,
        'notification_time' => '08:30',
        'days_of_week' => ['monday', 'wednesday'],
        'start_date' => now()->toDateString(),
        'end_date' => now()->addWeek()->toDateString(),
    ], $headers);

    $create->assertStatus(201);
    $create->assertJsonStructure(['data' => ['id', 'habit_id', 'notification_time', 'days_of_week']]);

    $id = $create->json('data.id');

    $update = $this->put("/api/habits/user/{$id}", [
        'notification_time' => '09:45',
    ], $headers);

    $update->assertOk();
    $update->assertJsonPath('data.notification_time', '09:45');

    $delete = $this->delete("/api/habits/user/{$id}", [], $headers);
    $delete->assertOk();
    $delete->assertJsonStructure(['message']);
});
