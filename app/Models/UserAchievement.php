<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAchievement extends Model
{
    protected $fillable = [
        'user_id',
        'unlocked_at'
    ];

    protected $casts = [
        'unlocked_at' => 'datetime',
    ];

    protected $with = ['achievement'];

    public function achievement()
    {
        return $this->belongsTo(Achievement::class);
    }

    public static function unlock(string $identifier, User $user) {
        $achievement = Achievement::where('identifier', $identifier)->first();

        if (!$achievement) {
            throw new \Exception("Achievement with identifier {$identifier} not found.");
        }

        $userAchievement = self::where('user_id', $user->id)
            ->where('achievement_id', $achievement->id)
            ->first();

        if ($userAchievement->unlocked_at !== null) {
            return $userAchievement;
        }

        $userAchievement->unlocked_at = now();
        $userAchievement->save();

        return $userAchievement;
    }
}
