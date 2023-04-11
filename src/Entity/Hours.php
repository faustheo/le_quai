<?php

namespace App\Entity;

use App\Repository\HoursRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HoursRepository::class)]
class Hours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $LunchOpening = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $LunchClosing = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $DinnerOpening = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $DinnerClosing = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getLunchOpening(): ?\DateTimeInterface
    {
        return $this->LunchOpening;
    }

    public function setLunchOpening(?\DateTimeInterface $LunchOpening): self
    {
        $this->LunchOpening = $LunchOpening;

        return $this;
    }

    public function getLunchClosing(): ?\DateTimeInterface
    {
        return $this->LunchClosing;
    }

    public function setLunchClosing(?\DateTimeInterface $LunchClosing): self
    {
        $this->LunchClosing = $LunchClosing;

        return $this;
    }

    public function getDinnerOpening(): ?\DateTimeInterface
    {
        return $this->DinnerOpening;
    }

    public function setDinnerOpening(?\DateTimeInterface $DinnerOpening): self
    {
        $this->DinnerOpening = $DinnerOpening;

        return $this;
    }

    public function getDinnerClosing(): ?\DateTimeInterface
    {
        return $this->DinnerClosing;
    }

    public function setDinnerClosing(?\DateTimeInterface $DinnerClosing): self
    {
        $this->DinnerClosing = $DinnerClosing;

        return $this;
    }
}
