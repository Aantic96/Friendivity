<?php

namespace App\Services;

use App\Entity\FriendRequest;
use App\Entity\User;
use App\Enum\FriendRequestStatus;
use App\Repository\FriendRequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Security\Core\User\UserInterface;

class FriendRequestService
{
    public function __construct(
        protected EntityManagerInterface $entityManager, 
        protected FriendRequestRepository $repository
        )
    {
    }

    public function checkIfAlreadySent(UserInterface $user, UserInterface $recipient): ?FriendRequest
    {
        if($user->getId() == $recipient->getId()) {
            throw new Exception('Cannot add yourself to friend list');
        }

        $result = $this->repository->getFriendRequest($user, $recipient);

        if(!$result) {
            return null;
        }

        return $result;
    }

    public function acceptFriendRequest(UserInterface $user, int $id): ?FriendRequest
    {
        $friendRequest = $this->repository->getFriendRequestByRecipientAndId($user, $id);

        if(!$friendRequest) {
            throw new Exception('No FriendRequest under that id for the given user found');
        }

        if($friendRequest->getStatus() == FriendRequestStatus::ACCEPTED) {
            throw new Exception('You are already friends with this user');
        }

        return $this->repository->acceptFriendRequest($friendRequest);
    }
}