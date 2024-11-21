<?php

namespace App\Services;

use DateTime;
use Symfony\Component\Security\Core\User\UserInterface;

class CalendarEventService
{
    /**
     * Checks that event doesn't end before it even begins
     */
    public function appointmentTimeChecker(DateTime $startTime, DateTime $endTime): bool
    {
        if($endTime < $startTime || $endTime == $startTime){    
            return false;
        }

        return true;
    }
}