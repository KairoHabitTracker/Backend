<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use App\Models\User;

pest()->use(RefreshDatabase::class);

function authHeadersAvatar(): array {
    $user = User::create([
        'email' => 'avatar@example.com',
        'password' => 'password12345',
    ]);

    $user->info()->create([
        'name' => 'Avatar User',
        'avatar_url' => 'https://example.com/old.svg',
    ]);

    $token = $user->createToken('tests')->plainTextToken;

    return [
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json',
    ];
}

test('Update profile avatar', function () {
    Storage::fake('public');

    $headers = authHeadersAvatar();

    $file = UploadedFile::fake()->image('avatar.jpg');

    $response = $this->put('/api/profile/avatar', [
        'avatar' => $file,
    ], $headers);

    $response->assertOk();
    $response->assertJsonStructure(['message', 'avatar_url']);
});

test('Delete profile avatar', function () {
    Storage::fake('public');

    $headers = authHeadersAvatar();

    $response = $this->delete('/api/profile/avatar', [], $headers);

    $response->assertOk();
    $response->assertJsonStructure(['message', 'avatar_url']);
    $this->assertStringStartsWith('https://api.dicebear.com/9.x/identicon/svg?seed=', $response->json('avatar_url'));
});
