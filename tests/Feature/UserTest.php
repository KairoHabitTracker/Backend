<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

pest()->use(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

function authHeadersUser(): array {
    $user = User::create([
        'email' => 'user_delete@example.com',
        'password' => 'password12345',
        'email_verified_at' => now(),
    ]);

    $token = $user->createToken('tests')->plainTextToken;

    return [
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json',
    ];
}

test('Delete user account', function () {
    $headers = authHeadersUser();

    $response = $this->delete('/api/account', [], $headers);

    $response->assertOk();
    $response->assertJsonStructure(['message']);
});

