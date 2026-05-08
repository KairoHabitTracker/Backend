<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

pest()->use(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

function authHeadersUserHealthLogs(): array {
    $user = User::create([
        'email' => 'healthlogs@example.com',
        'password' => 'password12345',
        'email_verified_at' => now(),
    ]);

    $token = $user->createToken('tests')->plainTextToken;

    return [
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json',
    ];
}

test('Sync user health logs', function () {
    $headers = authHeadersUserHealthLogs();

    $response = $this->post('/api/health/sync', [
        'date' => '2025-10-24',
        'steps' => 10000,
        'sleep_minutes' => 480
    ], $headers);

    $response->assertOk();
    $response->assertJsonStructure(['message']);
});

