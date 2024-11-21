<?php

namespace App\Entity;

use App\Repository\CalendarEventRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CalendarEventRepository::class)]
class CalendarEvent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'calendarEvents')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $appointment = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $AppointmentEnd = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getAppointment(): ?\DateTimeInterface
    {
        return $this->appointment;
    }

    public function setAppointment(\DateTimeInterface $appointment): static
    {
        $this->appointment = $appointment;

        return $this;
    }

    public function getAppointmentEnd(): ?\DateTimeInterface
    {
        return $this->AppointmentEnd;
    }

    public function setAppointmentEnd(\DateTimeInterface $AppointmentEnd): static
    {
        $this->AppointmentEnd = $AppointmentEnd;

        return $this;
    }
}
