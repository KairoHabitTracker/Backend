<?php

namespace App\Models;

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
}
