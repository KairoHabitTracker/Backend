<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

pest()->use(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

function authHeaders(): array {
    $user = User::create([
        'email' => 'profile@example.com',
        'password' => 'password12345',
        'email_verified_at' => now(),
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

test('Onboard profile', function () {
    $headers = authHeaders();

    $response = $this->postJson('/api/profile/onboard', [
        'name' => 'Onboarded User',
        'age' => 25,
        'interests' => ['health', 'work', 'social']
    ], $headers);

    $response->assertOk();
    $response->assertJsonPath('data.info.name', 'Onboarded User');
    $response->assertJsonPath('data.info.age', 25);
    $this->assertNotNull($response->json('data.info.onboarded_at'));
    $this->assertCount(3, $response->json('data.info.interests'));
});
