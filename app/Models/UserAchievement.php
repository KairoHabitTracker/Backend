<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAchievement extends Model
{
    protected $fillable = [
        'user_id',
        'achievement_id',
        'progress',
        'unlocked_at'
    ];

    protected $casts = [
        'unlocked_at' => 'datetime',
    ];

    public function achievement()
    {
        return $this->belongsTo(Achievement::class);
    }

    public function addProgress()
    {
        if (is_null($this->unlocked_at)) {
            $this->progress += 1;
            if($this->progress == $this->achievement()->goal_value) {
                $this->unlocked_at = now();
            }
            $this->save();
        }
    }
}
