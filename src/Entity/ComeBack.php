<?php

namespace App\Entity;

use App\Repository\ComeBackRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Timestampable;

#[ORM\Entity(repositoryClass: ComeBackRepository::class)]
class ComeBack
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $lastName;

    #[ORM\Column(type: 'string', length: 255)]
    private $firstName;

    #[ORM\Column(type: 'string', length: 255)]
    private $Address;

    #[ORM\Column(type: 'string', length: 255)]
    private $mobile;

    #[ORM\Column(type: 'date')]
    private $borthDate;

    #[ORM\Column(type: 'string', length: 20)]
    private $nationalNumber;

    #[ORM\OneToOne(mappedBy: 'comeBack', targetEntity: Animals::class, cascade: ['persist', 'remove'])]
    private $animals;

    #[ORM\Column(type: 'datetime')]
    #[Timestampable(on: 'create')]
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->Address;
    }

    public function setAddress(string $Address): self
    {
        $this->Address = $Address;

        return $this;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(string $mobile): self
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function getBorthDate(): ?\DateTimeInterface
    {
        return $this->borthDate;
    }

    public function setBorthDate(\DateTimeInterface $borthDate): self
    {
        $this->borthDate = $borthDate;

        return $this;
    }

    public function getNationalNumber(): ?string
    {
        return $this->nationalNumber;
    }

    public function setNationalNumber(string $nationalNumber): self
    {
        $this->nationalNumber = $nationalNumber;

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
            $this->animals->setComeBack(null);
        }

        // set the owning side of the relation if necessary
        if ($animals !== null && $animals->getComeBack() !== $this) {
            $animals->setComeBack($this);
        }

        $this->animals = $animals;

        return $this;
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
}
