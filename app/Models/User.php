<?php

namespace App\Models;

use Faker\Factory;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail, CanResetPassword
{
    use Notifiable, HasApiTokens, \Illuminate\Auth\Passwords\CanResetPassword;

    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();

        static::creating(function (User $user) {
            $user->id = Str::ulid();
        });

        static::created(function (User $user) {
            $faker = Factory::create();

            $user->info()->create([
                'name' => ucfirst(explode('@', $user->email)[0]),
                'avatar_url' => 'https://api.dicebear.com/9.x/identicon/svg?seed=' . $faker->uuid(),
            ]);

            Achievement::all()->each(function ($achievement) use ($user) {
                $user->achievements()->create([
                    'achievement_id' => $achievement->id,
                    'unlocked_at' => null,
                ]);
            });
        });
    }

    protected $with = ['info'];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'password',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function info()
    {
        return $this->hasOne(UserInfo::class);
    }

    public function habits()
    {
        return $this->hasMany(UserHabit::class);
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    public function friendsAsUser1()
    {
        return $this->belongsToMany(User::class, 'friends', 'user1_id', 'user2_id');
    }

    public function friendsAsUser2()
    {
        return $this->belongsToMany(User::class, 'friends', 'user2_id', 'user1_id');
    }

    public function getFriendsAttribute()
    {
        return $this->friendsAsUser1->merge($this->friendsAsUser2);
    }


    public function friendRequestsOfMine()
    {
        return $this->hasMany(FriendRequest::class, 'sender_id');
    }

    public function friendRequestsToMe()
    {
        return $this->hasMany(FriendRequest::class, 'receiver_id');
    }

    public function achievements()
    {
        return $this->hasMany(UserAchievement::class, 'user_id');
    }

    public function completions()
    {
        return $this->hasMany(UserHabitCompletion::class, 'user_habit_id');
    }

    public function healthLogs()
    {
        return $this->hasMany(UserHealthLog::class, 'user_id');
    }
}
