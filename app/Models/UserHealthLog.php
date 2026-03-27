<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserHealthLog extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'steps',
        'sleep_minutes'
    ];

    protected $casts = [
        'date' => 'date'
    ];
}
