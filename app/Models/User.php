<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\FriendRequestStatus;
use Faker\Factory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasApiTokens;

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

    public function friends()
    {
        return $this->belongsToMany(User::class, 'friends', 'user1_id', 'user2_id')
            ->wherePivotIn('user1_id', [$this->id])
            ->orWherePivotIn('user2_id', [$this->id]);
    }

    public function friendRequestsOfMine()
    {
        return $this->belongsToMany(FriendRequest::class, 'friend_requests', 'sender_id', 'receiver_id');
    }

    public function friendRequestsToMe()
    {
        return $this->belongsToMany(FriendRequest::class, 'friend_requests', 'receiver_id', 'sender_id');
    }
}
