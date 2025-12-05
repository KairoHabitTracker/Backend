<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class UserHabitCompletion extends Model
{
    protected $fillable = [
        'user_habit_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function userHabit()
    {
        return $this->belongsTo(UserHabit::class);
    }

    public function user()
    {
        return $this->userHabit->user;
    }
}
