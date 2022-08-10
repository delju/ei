<?php

namespace App\Entity;

use App\Repository\AnimalsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Slug;
use Gedmo\Mapping\Annotation\Timestampable;

#[ORM\Entity(repositoryClass: AnimalsRepository::class)]
class Animals
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 80, nullable: true)]
    private $name;

    #[ORM\Column(type: 'boolean')]
    private $Sexe;

    #[ORM\Column(type: 'date')]
    private $borthDate;

    #[ORM\Column(type: 'boolean')]
    private $castrate;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $coat;

    #[ORM\Column(type: 'text', nullable: true)]
    private $personality;

    #[ORM\Column(type: 'boolean')]
    private $lastChance;

    #[ORM\Column(type: 'text', nullable: true)]
    private $health;

    #[ORM\ManyToOne(targetEntity: Species::class, inversedBy: 'animals')]
    #[ORM\JoinColumn(nullable: false)]
    private $species;

    #[ORM\ManyToMany(targetEntity: GetOn::class, inversedBy: 'animals')]
    private $getOn;

    #[ORM\ManyToOne(targetEntity: ArrivalReason::class, inversedBy: 'animals')]
    #[ORM\JoinColumn(nullable: false)]
    private $arrivalReason;

    #[ORM\OneToOne(inversedBy: 'animals', targetEntity: death::class, cascade: ['persist', 'remove'])]
    private $death;

    #[ORM\OneToOne(inversedBy: 'animals', targetEntity: Adoption::class, cascade: ['persist', 'remove'])]
    private $Adoption;

    #[ORM\OneToOne(inversedBy: 'animals', targetEntity: ComeBack::class, cascade: ['persist', 'remove'])]
    private $comeBack;

    #[ORM\OneToMany(mappedBy: 'animals', targetEntity: Treatment::class)]
    private $treatments;

    #[ORM\Column(type: 'datetime')]
    #[Timestampable(on: 'create')]
    private $dateArrival;

    #[ORM\Column(type: 'string', length: 255)]
    #[Slug(fields: ['name'], updatable: false )]
    private $slug;

    #[ORM\OneToOne(inversedBy: 'animals', targetEntity: Gallery::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $gallery;

    #[ORM\ManyToOne(targetEntity: Races::class, inversedBy: 'animals')]
    private $races;

    public function __construct()
    {
        $this->getOn = new ArrayCollection();
        $this->treatments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSexe(): ?bool
    {
        return $this->Sexe;
    }

    public function setSexe(bool $Sexe): self
    {
        $this->Sexe = $Sexe;

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

    public function getCastrate(): ?bool
    {
        return $this->castrate;
    }

    public function setCastrate(bool $castrate): self
    {
        $this->castrate = $castrate;

        return $this;
    }

    public function getCoat(): ?string
    {
        return $this->coat;
    }

    public function setCoat(?string $coat): self
    {
        $this->coat = $coat;

        return $this;
    }

    public function getPersonality(): ?string
    {
        return $this->personality;
    }

    public function setPersonality(?string $personality): self
    {
        $this->personality = $personality;

        return $this;
    }

    public function getLastChance(): ?bool
    {
        return $this->lastChance;
    }

    public function setLastChance(bool $lastChance): self
    {
        $this->lastChance = $lastChance;

        return $this;
    }

    public function getHealth(): ?string
    {
        return $this->health;
    }

    public function setHealth(?string $health): self
    {
        $this->health = $health;

        return $this;
    }

    public function getSpecies(): ?Species
    {
        return $this->species;
    }

    public function setSpecies(?Species $species): self
    {
        $this->species = $species;

        return $this;
    }

    /**
     * @return Collection<int, GetOn>
     */
    public function getGetOn(): Collection
    {
        return $this->getOn;
    }

    public function addGetOn(GetOn $getOn): self
    {
        if (!$this->getOn->contains($getOn)) {
            $this->getOn[] = $getOn;
        }

        return $this;
    }

    public function removeGetOn(GetOn $getOn): self
    {
        $this->getOn->removeElement($getOn);

        return $this;
    }

    public function getArrivalReason(): ?ArrivalReason
    {
        return $this->arrivalReason;
    }

    public function setArrivalReason(?ArrivalReason $arrivalReason): self
    {
        $this->arrivalReason = $arrivalReason;

        return $this;
    }

    public function getDeath(): ?death
    {
        return $this->death;
    }

    public function setDeath(?death $death): self
    {
        $this->death = $death;

        return $this;
    }

    public function getAdoption(): ?Adoption
    {
        return $this->Adoption;
    }

    public function setAdoption(?Adoption $Adoption): self
    {
        $this->Adoption = $Adoption;

        return $this;
    }

    public function getComeBack(): ?ComeBack
    {
        return $this->comeBack;
    }

    public function setComeBack(?ComeBack $comeBack): self
    {
        $this->comeBack = $comeBack;

        return $this;
    }

    /**
     * @return Collection<int, Treatment>
     */
    public function getTreatments(): Collection
    {
        return $this->treatments;
    }

    public function addTreatment(Treatment $treatment): self
    {
        if (!$this->treatments->contains($treatment)) {
            $this->treatments[] = $treatment;
            $treatment->setAnimals($this);
        }

        return $this;
    }

    public function removeTreatment(Treatment $treatment): self
    {
        if ($this->treatments->removeElement($treatment)) {
            // set the owning side to null (unless already changed)
            if ($treatment->getAnimals() === $this) {
                $treatment->setAnimals(null);
            }
        }

        return $this;
    }

    public function getDateArrival(): ?\DateTimeInterface
    {
        return $this->dateArrival;
    }

    public function setDateArrival(\DateTimeInterface $dateArrival): self
    {
        $this->dateArrival = $dateArrival;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getGallery(): ?Gallery
    {
        return $this->gallery;
    }

    public function setGallery(Gallery $gallery): self
    {
        $this->gallery = $gallery;

        return $this;
    }

    public function getRaces(): ?Races
    {
        return $this->races;
    }

    public function setRaces(?Races $races): self
    {
        $this->races = $races;

        return $this;
    }
}
