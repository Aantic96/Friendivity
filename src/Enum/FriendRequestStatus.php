<?php

namespace App\Enum;

enum FriendRequestStatus: string
{
    case PENDING = 'pending';
    case CANCELLED = 'cancelled';
    case ACCEPTED = 'accepted';
}
