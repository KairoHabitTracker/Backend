<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\FriendRequest;
use App\Enums\FriendRequestStatus;

pest()->use(RefreshDatabase::class);

beforeEach(function () {
    $this->seed();
});

function authHeadersFriendRequests(): array {
    $user = User::create([
        'email' => 'friend_req1@example.com',
        'password' => 'password12345',
        'email_verified_at' => now(),
    ]);

    $token = $user->createToken('tests')->plainTextToken;

    return [
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json',
    ];
}

test('List received friend requests', function () {
    $headers = authHeadersFriendRequests();
    $response = $this->get('/api/friend-requests/received', $headers);
    $response->assertOk();
    $response->assertJsonStructure(['data']);
});

test('List sent friend requests', function () {
    $headers = authHeadersFriendRequests();
    $response = $this->get('/api/friend-requests/sent', $headers);
    $response->assertOk();
    $response->assertJsonStructure(['data']);
});

test('Send a friend request', function () {
    $headers = authHeadersFriendRequests();

    $friend = User::create([
        'email' => 'friend_req2@example.com',
        'password' => 'password12345',
        'email_verified_at' => now(),
    ]);

    $response = $this->post('/api/friend-requests', [
        'friend_id' => $friend->id->toString(),
    ], $headers);

    $response->assertOk();
    $response->assertJsonStructure(['message']);
});

test('Accept a friend request', function () {
    $headers = authHeadersFriendRequests();

    $user = User::where('email', 'friend_req1@example.com')->first();

    $sender = User::create([
        'email' => 'sender_accept@example.com',
        'password' => 'password12345',
        'email_verified_at' => now(),
    ]);

    $fr = $sender->friendRequestsOfMine()->create([
        'receiver_id' => $user->id,
    ]);

    $response = $this->post("/api/friend-requests/accept/{$fr->id}", [], $headers);
    $response->assertOk();
    $response->assertJsonStructure(['message']);
});

test('Reject a friend request', function () {
    $headers = authHeadersFriendRequests();

    $user = User::where('email', 'friend_req1@example.com')->first();

    $sender = User::create([
        'email' => 'sender_reject@example.com',
        'password' => 'password12345',
        'email_verified_at' => now(),
    ]);

    $fr = $sender->friendRequestsOfMine()->create([
        'receiver_id' => $user->id,
    ]);

    $response = $this->post("/api/friend-requests/reject/{$fr->id}", [], $headers);
    $response->assertOk();
    $response->assertJsonStructure(['message']);
});
