<?php

namespace App\Entity;

use App\Repository\EventsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventsRepository::class)]
class Events
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?int $year = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $short_text = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $event_text = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $event_picture = null;

    #[ORM\Column(nullable: true)]
    private ?float $longitude = null;

    #[ORM\Column(nullable: true)]
    private ?float $latitude = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    private ?EventType $event_type = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    private ?EventPeriod $event_period = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getShortText(): ?string
    {
        return $this->short_text;
    }

    public function setShortText(?string $short_text): static
    {
        $this->short_text = $short_text;

        return $this;
    }

    public function getEventText(): ?string
    {
        return $this->event_text;
    }

    public function setEventText(?string $event_text): static
    {
        $this->event_text = $event_text;

        return $this;
    }

    public function getEventPicture(): ?string
    {
        return $this->event_picture;
    }

    public function setEventPicture(?string $event_picture): static
    {
        $this->event_picture = $event_picture;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getEventType(): ?EventType
    {
        return $this->event_type;
    }

    public function setEventType(?EventType $event_type): static
    {
        $this->event_type = $event_type;

        return $this;
    }

    public function getEventPeriod(): ?EventPeriod
    {
        return $this->event_period;
    }

    public function setEventPeriod(?EventPeriod $event_period): static
    {
        $this->event_period = $event_period;

        return $this;
    }
}
