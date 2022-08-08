<?php

namespace App\Entity;

use App\Repository\DeathRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeathRepository::class)]
class Death
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'date')]
    private $date;

    #[ORM\Column(type: 'string', length: 255)]
    private $cause;

    #[ORM\OneToOne(mappedBy: 'death', targetEntity: Animals::class, cascade: ['persist', 'remove'])]
    private $animals;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getCause(): ?string
    {
        return $this->cause;
    }

    public function setCause(string $cause): self
    {
        $this->cause = $cause;

        return $this;
    }

    public function getAnimals(): ?Animals
    {
        return $this->animals;
    }

    public function setAnimals(?Animals $animals): self
    {
        // unset the owning side of the relation if necessary
        if ($animals === null && $this->animals !== null) {
            $this->animals->setDeath(null);
        }

        // set the owning side of the relation if necessary
        if ($animals !== null && $animals->getDeath() !== $this) {
            $animals->setDeath($this);
        }

        $this->animals = $animals;

        return $this;
    }
}
