<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

pest()->use(RefreshDatabase::class);

function authHeaders(): array {
    $user = User::create([
        'email' => 'user@example.com',
        'password' => 'password12345', // hashed by cast
    ]);

    $user->info()->create([
        'name' => 'Test User',
        'avatar_url' => 'https://example.com/avatar.svg',
    ]);

    $token = $user->createToken('tests')->plainTextToken;

    return [
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json',
    ];
}

test('Get current profile', function () {
    $headers = authHeaders();

    $response = $this->get('/api/profile', $headers);

    $response->assertOk();
    $response->assertJsonStructure(['data' => ['id', 'email', 'info' => ['name', 'avatar_url']]]);
});


test('Update current profile', function () {
    $headers = authHeaders();

    $response = $this->put('/api/profile', [
        'name' => 'Updated Name',
    ], $headers);

    $response->assertOk();
    $response->assertJsonPath('data.info.name', 'Updated Name');
});
