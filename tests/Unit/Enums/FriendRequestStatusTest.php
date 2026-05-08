<?php

use App\Enums\FriendRequestStatus;

test('FriendRequestStatus has correct string backed values', function () {
    expect(FriendRequestStatus::PENDING->value)->toBe('pending')
        ->and(FriendRequestStatus::REJECTED->value)->toBe('rejected');
});

test('FriendRequestStatus contains exactly the expected number of cases', function () {
    $cases = array_map(fn($enum) => $enum->value, FriendRequestStatus::cases());

    expect($cases)->toContain('pending', 'rejected');
});

