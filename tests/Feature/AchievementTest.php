<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

pest()->use(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

function authHeadersAchievements(): array {
    $user = User::create([
        'email' => 'achievements@example.com',
        'password' => 'password12345',
        'email_verified_at' => now(),
    ]);

    $token = $user->createToken('tests')->plainTextToken;

    return [
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json',
    ];
}

test('List user achievements', function () {
    $headers = authHeadersAchievements();

    $response = $this->get('/api/achievements', $headers);

    $response->assertOk();
    $response->assertJsonStructure([
        'data'
    ]);
});

