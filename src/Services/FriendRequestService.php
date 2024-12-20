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
    public function __construct(protected EntityManagerInterface $entityManager, protected FriendRequestRepository $repository)
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
}