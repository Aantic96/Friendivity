<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class CalendarEventRequest
{
    #[Assert\NotBlank(message: "The appointment_start field is required.")]
    #[Assert\DateTime(format: "Y-m-d H:i", message: "The appointmentStart must be a valid datetime in 'Y-m-d H:i' format.")]
    public string $appointmentStart;

    #[Assert\NotBlank(message: "The appointment_end field is required.")]
    #[Assert\DateTime(format: "Y-m-d H:i", message: "The appointmentEnd must be a valid datetime in 'Y-m-d H:i' format.")]
    public string $appointmentEnd;
}
