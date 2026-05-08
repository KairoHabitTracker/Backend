<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Password;

pest()->use(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

test('Send password reset link', function () {
    User::create([
        'email' => 'forgot@example.com',
        'password' => 'password12345',
    ]);

    $response = $this->post('/api/password/forgot-password', [
        'email' => 'forgot@example.com'
    ]);

    $response->assertOk();
    $response->assertJsonStructure(['message']);
});

