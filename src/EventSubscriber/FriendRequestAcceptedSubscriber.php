<?php

namespace App\EventSubscriber;

use App\Event\FriendRequestAcceptedEvent;
use App\Repository\FriendRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FriendRequestAcceptedSubscriber implements EventSubscriberInterface
{
    private EntityManagerInterface $entityManager;
    private FriendRepository $friendRepository;

    public function __construct(EntityManagerInterface $entityManager, FriendRepository $friendRepository)
    {
        $this->entityManager = $entityManager;
        $this->friendRepository = $friendRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FriendRequestAcceptedEvent::class => 'onFriendRequestAccepted',
        ];
    }

    public function onFriendRequestAccepted(
        FriendRequestAcceptedEvent $event
        ): void
    {
        $friendRequest = $event->getFriendRequest();
        $this->friendRepository->createFriendshipForUsers($friendRequest);
    }
}