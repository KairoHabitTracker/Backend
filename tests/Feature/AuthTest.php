<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

pest()->use(RefreshDatabase::class);

test('Register a user', function () {
    $this->seed();

    $response = $this->post('/api/auth/register', [
        'email' => 'example@test.com',
        'password' => 'password123',
        'device_name' => 'test_device'
    ]);

    $response->assertStatus(201);
    $response->assertJsonStructure(['message']);
});

test('Login and receive a token', function () {
    $user = User::create([
        'email' => 'login@test.com',
        'password' => 'password12345',
    ]);
    $user->info()->create([
        'name' => 'Login User',
        'avatar_url' => 'https://example.com/a.svg',
    ]);

    $response = $this->post('/api/auth/login', [
        'email' => 'login@test.com',
        'password' => 'password12345',
        'device_name' => 'test_device',
    ]);

    $response->assertOk();
    $response->assertJsonStructure(['token']);
});

test('Logout current session', function () {
    $user = User::create([
        'email' => 'logout@test.com',
        'password' => 'password12345',
    ]);
    $user->info()->create([
        'name' => 'Logout User',
        'avatar_url' => 'https://example.com/a.svg',
    ]);

    $token = $user->createToken('tests')->plainTextToken;

    $response = $this->post('/api/auth/logout', [], [
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json',
    ]);

    $response->assertOk();
    $response->assertJsonStructure(['message']);
});

test('Logout all sessions', function () {
    $user = User::create([
        'email' => 'logoutall@test.com',
        'password' => 'password12345',
    ]);
    $user->info()->create([
        'name' => 'Logout All User',
        'avatar_url' => 'https://example.com/a.svg',
    ]);

    $user->createToken('tests1');
    $token = $user->createToken('tests2')->plainTextToken;

    $response = $this->post('/api/auth/logout-all', [], [
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json',
    ]);

    $response->assertOk();
    $response->assertJsonStructure(['message']);
});
