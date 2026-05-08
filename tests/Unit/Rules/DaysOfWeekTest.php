<?php

use App\Rules\DaysOfWeek;

test('validates correctly with an array of valid days', function () {
    $rule = new DaysOfWeek();

    $failCalled = false;
    $rule->validate('days', ['monday', 'tuesday'], function ($message) use (&$failCalled) {
        $failCalled = true;
    });

    expect($failCalled)->toBeFalse();
});

test('validates with different case days', function () {
    $rule = new DaysOfWeek();

    $failCalled = false;
    $rule->validate('days', ['Monday', 'TUESDAY'], function ($message) use (&$failCalled) {
        $failCalled = true;
    });

    expect($failCalled)->toBeFalse();
});

test('fails if value is not an array', function () {
    $rule = new DaysOfWeek();

    $failCalled = false;
    $rule->validate('days', 'monday', function ($message) use (&$failCalled) {
        $failCalled = true;
        expect($message)->toBe('The :attribute must be an array of days of the week.');
    });

    expect($failCalled)->toBeTrue();
});

test('fails if value contains invalid days', function () {
    $rule = new DaysOfWeek();

    $failCalled = false;
    $rule->validate('days', ['monday', 'funday'], function ($message) use (&$failCalled) {
        $failCalled = true;
        expect($message)->toBe("The day 'funday' is not a valid day of the week. Valid days are: monday, tuesday, wednesday, thursday, friday, saturday, sunday.");
    });

    expect($failCalled)->toBeTrue();
});

