<?php

namespace App\Models;

use App\Enums\HabitCategory;
use Illuminate\Database\Eloquent\Model;

class UserInterest extends Model
{
    protected $table = 'user_interests';

    protected $fillable = [
        'user_id',
        'interest',
    ];

    protected $casts = [
        'interest' => HabitCategory::class,
    ];
}
