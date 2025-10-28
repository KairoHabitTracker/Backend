<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

pest()->use(RefreshDatabase::class);

function authHeadersHabits(): array {
    $user = User::create([
        'email' => 'habits@example.com',
        'password' => 'password12345',
    ]);

    $user->info()->create([
        'name' => 'Habits User',
        'avatar_url' => 'https://example.com/a.svg',
    ]);

    $token = $user->createToken('tests')->plainTextToken;

    return [
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json',
    ];
}

test('List default habits', function () {
    $this->seed();

    $headers = authHeadersHabits();

    $response = $this->get('/api/habits', $headers);

    $response->assertOk();
    $response->assertJsonStructure([
        'data' => [
            '*' => ['id', 'name', 'emoji', 'hex_color', 'category']
        ]
    ]);
});
