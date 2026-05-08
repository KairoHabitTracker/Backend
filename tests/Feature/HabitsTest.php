<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

pest()->use(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

function authHeadersHabits(): array {
    $user = User::create([
        'email' => 'habits@example.com',
        'password' => 'password12345',
        'email_verified_at' => now(),
    ]);

    $token = $user->createToken('tests')->plainTextToken;

    return [
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json',
    ];
}

test('List default habits', function () {
    $headers = authHeadersHabits();

    $response = $this->get('/api/habits', $headers);

    $response->assertOk();
    $response->assertJsonStructure([
        'data' => [
            '*' => ['id', 'name', 'emoji', 'hex_color', 'category']
        ]
    ]);
});

test('Create, update, delete custom habits', function () {
    $headers = authHeadersHabits();

    $response = $this->postJson('/api/habits/custom', [
        'name' => 'Custom habit',
        'emoji' => '🚀',
        'hex_color' => '#123456',
        'category' => 'health'
    ], $headers);

    $response->assertStatus(201);
    $id = $response->json('data.id');

    $response = $this->get('/api/habits/custom', $headers);
    $response->assertOk();
    $response->assertJsonCount(1, 'data');

    $response = $this->putJson("/api/habits/custom/{$id}", [
        'name' => 'Updated habit'
    ], $headers);
    $response->assertOk();
    $response->assertJsonPath('data.name', 'Updated habit');

    $response = $this->delete("/api/habits/custom/{$id}", [], $headers);
    $response->assertOk();

    $response = $this->get('/api/habits/custom', $headers);
    $response->assertOk();
    $response->assertJsonCount(0, 'data');
});
