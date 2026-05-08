<?php

use App\Enums\HabitCategory;

test('HabitCategory has correct string backed values', function () {
    expect(HabitCategory::HEALTH->value)->toBe('health')
        ->and(HabitCategory::SPORT->value)->toBe('sport')
        ->and(HabitCategory::WORK->value)->toBe('work')
        ->and(HabitCategory::CHORES->value)->toBe('chores')
        ->and(HabitCategory::COMMITMENTS->value)->toBe('commitments')
        ->and(HabitCategory::PHYSICAL_WELLBEING->value)->toBe('physical_wellbeing')
        ->and(HabitCategory::MENTAL_WELLBEING->value)->toBe('mental_wellbeing')
        ->and(HabitCategory::SOCIAL->value)->toBe('social')
        ->and(HabitCategory::FINANCIAL->value)->toBe('financial')
        ->and(HabitCategory::HOBBIES->value)->toBe('hobbies')
        ->and(HabitCategory::LEARNING->value)->toBe('learning')
        ->and(HabitCategory::PRODUCTIVITY->value)->toBe('productivity')
        ->and(HabitCategory::BAD_HABIT->value)->toBe('bad_habit')
        ->and(HabitCategory::OTHER->value)->toBe('other');
});

