<?php

namespace App\Enums;

enum FriendRequestStatus : string
{
    case PENDING = 'pending';
    case REJECTED = 'rejected';
}
