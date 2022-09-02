<?php

namespace App\Search;

class Search
{
    private ?string $keyword = null;
    private $species;
    private $getOns;
    private $sexe;
    private $lastChance;

    /**
     * @return mixed
     */
    public function getSpecies()
    {
        return $this->species;
    }

    /**
     * @param mixed $species
     */
    public function setSpecies($species): void
    {
        $this->species = $species;
    }

    /**
     * @return mixed
     */
    public function getGetOns()
    {
        return $this->getOns;
    }

    /**
     * @param mixed $getOns
     */
    public function setGetOns($getOns): void
    {
        $this->getOns = $getOns;
    }

    /**
     * @return mixed
     */
    public function getSexe()
    {
        return $this->sexe;
    }

    /**
     * @param mixed $sexe
     */
    public function setSexe($sexe): void
    {
        $this->sexe = $sexe;
    }

    /**
     * @return mixed
     */
    public function getLastChance()
    {
        return $this->lastChance;
    }

    /**
     * @param mixed $lastChance
     */
    public function setLastChance(mixed $lastChance): void
    {
        $this->lastChance = $lastChance;
    }

    /**
     * @return string|null
     */
    public function getKeyword(): ?string
    {
        return $this->keyword;
    }

    /**
     * @param string|null $keyword
     */
    public function setKeyword(?string $keyword): void
    {
        $this->keyword = $keyword;
    }

    public function __toString()
    {
        return 'search';
    }

}