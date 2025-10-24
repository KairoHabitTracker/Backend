<?php

namespace App\Models;

use App\Enums\HabitCategory;
use Illuminate\Database\Eloquent\Model;

class Habit extends Model
{
    protected $fillable = [
        'name',
        'emoji',
        'hex_color',
        'category',
    ];

    protected $casts = [
        'category' => HabitCategory::class,
    ];
}
