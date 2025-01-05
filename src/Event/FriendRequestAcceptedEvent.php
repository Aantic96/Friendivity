<?php

namespace App\Event;

use App\Entity\FriendRequest;

class FriendRequestAcceptedEvent
{
    private FriendRequest $friendRequest;

    public function __construct(FriendRequest $friendRequest)
    {
        $this->friendRequest = $friendRequest;
    }

    public function getFriendRequest(): FriendRequest
    {
        return $this->friendRequest;
    }
}