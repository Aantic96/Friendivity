<?php

namespace App\EventListener;

use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;

#[AsEntityListener(event: Events::preUpdate, method: 'onPreUpdate', entity: User::class)]    
final class RegistrationDateConfirmationListener
{

    public function __construct(
        private EntityManagerInterface $entityManager
        ) 
    {
    }

    public function onPreUpdate(User $user, PreUpdateEventArgs $event): void
    {
        if ($event->hasChangedField('isVerified')) {

            $oldValue = $event->getOldValue('isVerified');
            $newValue = $event->getNewValue('isVerified');

            if ($oldValue !== $newValue) {

                $user->setRegistrationDate(new DateTime());
            }
        }
    }
}
