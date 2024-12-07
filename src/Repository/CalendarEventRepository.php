<?php

namespace App\Repository;

use App\Entity\CalendarEvent;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<CalendarEvent>
 */
class CalendarEventRepository extends ServiceEntityRepository
{
    public function __construct(protected ManagerRegistry $registry, protected EntityManagerInterface $entityManager)
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

    public function editCalendarEvent(
        UserInterface $user,
        int $calendarId, 
        DateTime $appointmentStart, 
        DateTime $appointmentEnd
        ): CalendarEvent|null
    {
        $calendarEvent = $this->entityManager->getRepository(CalendarEvent::class)->find($calendarId);

        if (!$calendarEvent) {
            throw new NotFoundHttpException(
                'No calendarEvent found for id '.$calendarId
            );
        }

        if($calendarEvent->getOwner()?->getId() !== $user->getId()) {
            throw new Exception('Only owner can edit CalendarEvent');
        }

        $calendarEvent->setAppointment($appointmentStart);
        $calendarEvent->setAppointmentEnd($appointmentEnd);

        $this->entityManager->flush();

        return $calendarEvent;
    }

    public function deleteCalendarEvent(
        UserInterface $user,
        int $calendarId, 
    ): void
    {
        $calendarEvent = $this->entityManager->getRepository(CalendarEvent::class)->find($calendarId);

        if (!$calendarEvent) {
            throw new NotFoundHttpException(
                'No calendarEvent found for id '.$calendarId
            );
        }

        if($calendarEvent->getOwner()?->getId() !== $user->getId()) {
            throw new Exception('Only owner can delete CalendarEvent');
        }

        $this->entityManager->remove($calendarEvent);
        $this->entityManager->flush();

        return;
    }

    public function getCalendarEvent(
        UserInterface $user,
        int $calendarId, 
    ): CalendarEvent
    {
        $calendarEvent = $this->entityManager->getRepository(CalendarEvent::class)->find($calendarId);

        if (!$calendarEvent) {
            throw new NotFoundHttpException(
                'No calendarEvent found for id '.$calendarId
            );
        }

        //TODO: Add logic that attendees can also see the event
        if($calendarEvent->getOwner()?->getId() !== $user->getId()) {
            throw new Exception('Only owner can get CalendarEvent');
        }

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
