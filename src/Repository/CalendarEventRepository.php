<?php

namespace App\Repository;

use App\Entity\CalendarEvent;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<CalendarEvent>
 */
class CalendarEventRepository extends ServiceEntityRepository
{
    public function __construct(protected ManagerRegistry $registry)
    {
        parent::__construct($registry, CalendarEvent::class);
    }


    public function createCalendarEvent(
        UserInterface $user, 
        DateTime $appointmentStart, 
        DateTime $appointmentEnd
        ): CalendarEvent|null
    {
        $calendarEvent = new CalendarEvent();
        $calendarEvent->setOwner($user);
        $calendarEvent->setAppointment($appointmentStart);
        $calendarEvent->setAppointmentEnd($appointmentEnd);

        $entityManager = $this->getEntityManager();
        $entityManager->persist($calendarEvent);
        $entityManager->flush();

        return $calendarEvent;
    }
//    /**
//     * @return CalendarEvent[] Returns an array of CalendarEvent objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CalendarEvent
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
