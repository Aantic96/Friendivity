<?php

namespace App\Services;

use Symfony\Component\Security\Core\User\UserInterface;

class CalendarEventService
{

    public function validateAppointment(UserInterface $user, string $startTime, string $endTime): array
    {
        if(!$user) {
            return [];
        }

        $timeCheck = $this->appointmentTimeChecker($startTime, $endTime);
        if(!$timeCheck) {
            return false;
        }

        return true;
    }

    /**
     * Checks that event doesn't end before it even begins
     */
    protected function appointmentTimeChecker($startTime, $endTime): bool
    {

    }
}