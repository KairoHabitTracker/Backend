<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Habit;

pest()->use(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

function authHeadersUserHabits(): array {
    $user = User::create([
        'email' => 'userhabits@example.com',
        'password' => 'password12345',
        'email_verified_at' => now(),
    ]);

    $token = $user->createToken('tests')->plainTextToken;

    return [
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json',
    ];
}

test('List user habits', function () {
    $headers = authHeadersUserHabits();

    $response = $this->get('/api/habits/user', $headers);

    $response->assertOk();
    $response->assertJsonStructure(['data']);
});

test('Create, update and delete a user habit', function () {
    $headers = authHeadersUserHabits();

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

test('Complete and uncomplete habit', function () {
    $headers = authHeadersUserHabits();

    $habit = Habit::first();

    $today = strtolower(now()->englishDayOfWeek);

    $create = $this->post('/api/habits/user', [
        'habit_id' => $habit->id,
        'days_of_week' => [$today],
    ], $headers);

    $id = $create->json('data.id');

    $complete = $this->post("/api/habits/user/{$id}/complete", [], $headers);
    $complete->assertOk();

    $completions = $this->get("/api/habits/user/{$id}/completions", $headers);
    $completions->assertOk();
    $this->assertCount(1, $completions->json('data'));

    $uncomplete = $this->post("/api/habits/user/{$id}/uncomplete", [], $headers);
    $uncomplete->assertOk();

    $completions = $this->get("/api/habits/user/{$id}/completions", $headers);
    $completions->assertOk();
    $this->assertCount(0, $completions->json('data'));
});
