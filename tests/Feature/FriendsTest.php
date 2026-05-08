<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\DB;

pest()->use(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

function authHeadersFriends(): array {
    $user = User::create([
        'email' => 'friends1@example.com',
        'password' => 'password12345',
        'email_verified_at' => now(),
    ]);

    $token = $user->createToken('tests')->plainTextToken;

    return [
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json',
    ];
}

test('List friends', function () {
    $headers = authHeadersFriends();
    $response = $this->get('/api/friends', $headers);
    $response->assertOk();
    $response->assertJsonStructure(['data']);
});

test('Remove a friend', function () {
    $user1 = User::create([
        'email' => 'friends_to_remove1@example.com',
        'password' => 'password12345',
        'email_verified_at' => now(),
    ]);
    $user2 = User::create([
        'email' => 'friends_to_remove2@example.com',
        'password' => 'password12345',
        'email_verified_at' => now(),
    ]);

    DB::table('friends')->insert([
        'user1_id' => $user1->id,
        'user2_id' => $user2->id,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $token = $user1->createToken('tests')->plainTextToken;
    $headers = [
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json',
    ];

    $response = $this->delete('/api/friends/' . $user2->id, [], $headers);
    $response->assertOk();

    $response = $this->get('/api/friends', $headers);
    $response->assertOk();
    $response->assertJsonMissing(['data' => [['id' => $user2->id]]]);
});

