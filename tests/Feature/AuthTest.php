<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->use(RefreshDatabase::class);

test('Register a user and receive a token', function () {
    $this->seed();

    $response = $this->post('/api/auth/register', [
        'name' => 'Test User',
        'email' => 'example@test.com',
        'password' => 'password123',
        'device_name' => 'test_device'
    ]);

    $response->assertStatus(201);
    $response->assertJsonStructure(['token']);
});
