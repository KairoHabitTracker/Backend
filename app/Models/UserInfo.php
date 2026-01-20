<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    protected $table = 'user_infos';

    protected $fillable = [
        'user_id',
        'name',
        'avatar_url',
        'streak',
        'coins',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected function avatarUrl(): Attribute {
        return Attribute::make(
            get: function ($value) {
                if (str_starts_with($value, 'http')) {
                    return $value;
                } else {
                    return asset('storage/avatars/' . $value);
                }
            }
        );
    }

    public function largestStreak(): Attribute {
        return Attribute::make(
            get: function () {
                return UserHabit::query()->where('user_id', $this->user_id)
                    ->whereHas('completions')
                    ->max('streak') ?? 0;
            }
        );
    }

    protected $appends = ['largest_streak'];
}
