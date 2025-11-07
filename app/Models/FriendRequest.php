<?php

namespace App\Models;

use App\Enums\FriendRequestStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FriendRequest extends Model
{
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'status',
        'responded_at',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
        'status' => FriendRequestStatus::class,
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function accept() {
        DB::table('friends')->insert([
            'user1_id' => $this->sender_id,
            'user2_id' => $this->receiver_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->delete();
    }

    public function reject() {
        $this->status = FriendRequestStatus::REJECTED;
        $this->responded_at = now();
        $this->save();
    }
}
