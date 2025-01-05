<?php

namespace App\Repository;

use App\Entity\FriendRequest;
use App\Entity\User;
use App\Enum\FriendRequestStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use App\Event\FriendRequestAcceptedEvent;

/**
 * @extends ServiceEntityRepository<FriendRequest>
 */
class FriendRequestRepository extends ServiceEntityRepository
{
    public function __construct(
        protected ManagerRegistry $registry, 
        protected EntityManagerInterface $entityManager,
        protected EventDispatcherInterface $eventDispatcher
        )
    {
        parent::__construct($registry, FriendRequest::class);
    }

    public function createFriendRequest(UserInterface $user, UserInterface $recipient): FriendRequest
    {
        $friendRequest = new FriendRequest();
        $friendRequest->setSender($user);
        $friendRequest->setRecipient($recipient);
        $friendRequest->setStatus(FriendRequestStatus::PENDING);

        $entityManager = $this->getEntityManager();
        $entityManager->persist($friendRequest);
        $entityManager->flush();

        return $friendRequest;
    }

    public function getFriendRequest(UserInterface $user, UserInterface $recipient): FriendRequest|null
    {
        return $this->entityManager->createQueryBuilder()
            ->select('fr')
            ->from(FriendRequest::class, 'fr')
            ->where(
                '(fr.sender = :user1 AND fr.recipient = :user2) OR (fr.sender = :user2 AND fr.recipient = :user1)'
            )
            ->setParameter('user1', $user)
            ->setParameter('user2', $recipient)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getFriendRequestByRecipientAndId(UserInterface $user, int $id): FriendRequest|null
    {
        return $this->entityManager->createQueryBuilder()
            ->select('fr')
            ->from(FriendRequest::class, 'fr')
            ->where(
                'fr.recipient = :user AND fr.id = :id'
            )
            ->setParameter('user', $user)
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getRecipientById(int $friendId): UserInterface
    {
        $recipient = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $friendId]);

        if(!$recipient) {
            throw new Exception('Unexisting recipient');
        }

        return $recipient;
    }

    public function deletePreviouslyCancelledFriendRequest(FriendRequest $oldRequest): void
    {
        $this->entityManager->remove($oldRequest);
        $this->entityManager->flush();
    }

    public function getAllRecievedFriendRequests(UserInterface $user, FriendRequestStatus $status): Query
    {
        return $this->entityManager->createQueryBuilder()
        ->select('fr')
        ->from(FriendRequest::class, 'fr')
        ->where(
            'fr.recipient = :user AND fr.status = :status'
        )
        ->setParameter('user', $user)
        ->setParameter('status', $status)
        ->getQuery();
    }

    public function getAllSentFriendRequests(UserInterface $user, FriendRequestStatus $status): Query
    {
        return $this->entityManager->createQueryBuilder()
        ->select('fr')
        ->from(FriendRequest::class, 'fr')
        ->where(
            'fr.sender = :user AND fr.status = :status'
        )
        ->setParameter('user', $user)
        ->setParameter('status', $status)
        ->getQuery();
    }

    public function acceptFriendRequest(FriendRequest $friendRequest): FriendRequest
    {
        $friendRequest->setStatus(FriendRequestStatus::ACCEPTED);

        $event = new FriendRequestAcceptedEvent($friendRequest);
        $this->eventDispatcher->dispatch($event);

        $entityManager = $this->getEntityManager();
        $entityManager->persist($friendRequest);
        $entityManager->flush();

        return $friendRequest;
    }

//    /**
//     * @return FriendRequest[] Returns an array of FriendRequest objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FriendRequest
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
