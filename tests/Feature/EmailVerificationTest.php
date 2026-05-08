<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\URL;

pest()->use(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

function authHeadersEmailVerification($verified = false): array {
    $user = User::create([
        'email' => 'email_verif@example.com',
        'password' => 'password12345',
        'email_verified_at' => $verified ? now() : null,
    ]);

    $token = $user->createToken('tests')->plainTextToken;

    return [
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json',
    ];
}

test('Check email verification status', function () {
    $headers = authHeadersEmailVerification();
    $response = $this->get('/api/email/verify', $headers);
    $response->assertOk();
});

test('Send email verification notification', function () {
    $headers = authHeadersEmailVerification();
    $response = $this->post('/api/email/verification-notification', [], $headers);
    $response->assertOk();
});

