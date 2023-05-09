<?php

namespace App\Entity;

use App\Repository\MaxGuestsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MaxGuestsRepository::class)]
class MaxGuests
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "integer")]
    private int $availableSeats;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAvailableSeats(): int
    {
        return $this->availableSeats;
    }

    public function setAvailableSeats(int $availableSeats): self
    {
        $this->availableSeats = $availableSeats;

        return $this;
    }
}
