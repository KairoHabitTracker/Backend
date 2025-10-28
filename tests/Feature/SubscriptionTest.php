<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

pest()->use(RefreshDatabase::class);

function authHeadersSub(): array {
    $user = User::create([
        'email' => 'sub@example.com',
        'password' => 'password12345',
    ]);

    $user->info()->create([
        'name' => 'Sub User',
        'avatar_url' => 'https://example.com/a.svg',
    ]);

    $token = $user->createToken('tests')->plainTextToken;

    return [
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json',
    ];
}

test('Get subscription returns 404 when not found', function () {
    $headers = authHeadersSub();

    $response = $this->get('/api/subscription', $headers);

    $response->assertStatus(404);
});

test('Get subscription when exists', function () {
    $user = User::create([
        'email' => 'sub2@example.com',
        'password' => 'password12345',
    ]);
    $user->info()->create([
        'name' => 'Sub User2',
        'avatar_url' => 'https://example.com/a.svg',
    ]);
    $user->subscription()->create([
        'stripe_subscription_id' => 'sub_123',
        'status' => 'active',
        'current_period_start' => now(),
        'current_period_end' => now()->addMonth(),
    ]);

    $token = $user->createToken('tests')->plainTextToken;

    $response = $this->get('/api/subscription', [
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json',
    ]);

    $response->assertOk();
    $response->assertJsonStructure(['data' => ['id', 'user_id', 'status']]);
});
