<?php

namespace App\Repository;

use App\Entity\Friend;
use App\Entity\FriendRequest;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<Friend>
 */
class FriendRepository extends ServiceEntityRepository
{
    public function __construct(protected ManagerRegistry $registry, protected EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Friend::class);
    }

    public function createFriendshipForUsers(FriendRequest $friendRequest): void
    {
        $sender = $friendRequest->getSender();
        $recipient = $friendRequest->getRecipient();

        $firstFriend = new Friend();
        $firstFriend->setUser($sender);
        $firstFriend->setFriend($recipient);
        $firstFriend->setFriendsSince(new DateTime());
        $firstFriend->setFavorite(false);

        $secondFriend = new Friend();
        $secondFriend->setUser($recipient);
        $secondFriend->setFriend($sender);
        $secondFriend->setFriendsSince(new DateTime());
        $secondFriend->setFavorite(false);
        
        $this->entityManager->persist($firstFriend);
        $this->entityManager->persist($secondFriend);
        $this->entityManager->flush();
    }

    public function getFriends(UserInterface $user): Query
    {
        return $this->entityManager->createQueryBuilder()
        ->select('f')
        ->from(Friend::class, 'f')
        ->where(
            'f.user = :user'
        )
        ->setParameter('user', $user)
        ->getQuery();
    }

    public function getFriendByFriendId(UserInterface $user, int $id): ?Friend
    {
        return $this->entityManager->createQueryBuilder()
        ->select('f')
        ->from(Friend::class, 'f')
        ->where(
            'f.user = :user AND f.friend = :id'
        )
        ->setParameter('user', $user)
        ->setParameter('id', $id)
        ->getQuery()
        ->getOneOrNullResult();
    }

    public function addToFavorites(UserInterface $user, int $id): void
    {
        $friend = $this->getFriendByFriendId($user, $id);
        
        if(!$friend) {
            throw new Exception('No friend under given id found');
        }

        $friend = $friend->setFavorite(true);
        $this->entityManager->persist($friend);
        $this->entityManager->flush();
    }

    //    /**
    //     * @return Friend[] Returns an array of Friend objects
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

    //    public function findOneBySomeField($value): ?Friend
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
