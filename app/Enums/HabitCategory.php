<?php

namespace App\Enums;

enum HabitCategory: string
{
    case HEALTH = 'health';
    case SPORT = 'sport';
    case WORK = 'work';
    case CHORES = 'chores';
    case COMMITMENTS = 'commitments';
    case PHYSICAL_WELLBEING = 'physical_wellbeing';
    case MENTAL_WELLBEING = 'mental_wellbeing';
    case SOCIAL = 'social';
    case FINANCIAL = 'financial';
    case HOBBIES = 'hobbies';
    case LEARNING = 'learning';
    case PRODUCTIVITY = 'productivity';
    case BAD_HABIT = 'bad_habit';
    case OTHER = 'other';
}
