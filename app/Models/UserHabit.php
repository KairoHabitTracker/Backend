<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class UserHabit extends Model
{
    protected $fillable = [
        'user_id',
        'habit_id',
        'streak',
        'notification_time',
        'days_of_week',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'days_of_week' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'last_completed_at' => 'datetime',
    ];

    protected $with = ['habit'];

    protected $appends = ['last_completed_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function habit()
    {
        return $this->belongsTo(Habit::class);
    }

    public function completions()
    {
        return $this->hasMany(UserHabitCompletion::class);
    }

    protected function lastCompletedAt() {
        return Attribute::make(
            get: fn ($value) => $this->completions()->latest()->first()->created_at ?? null
        );
    }
}
