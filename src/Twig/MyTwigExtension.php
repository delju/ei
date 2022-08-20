<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class MyTwigExtension extends AbstractExtension
{

    public function getFilters(): array
    {
        return [
            new TwigFilter('age', [$this, 'ageFilter'])
        ];
    }

    /**
     * @throws \Exception
     */
    public function ageFilter($date): string
    {
        $dateborth = new \DateTime($date->format('d-m-Y'));
        $now = new \DateTime();
        $age = $now->diff($dateborth);

        return $age->y;
    }
}