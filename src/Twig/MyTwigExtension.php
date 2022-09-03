<?php

namespace App\Twig;

use App\Search\SearchFormGenerator;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class MyTwigExtension extends AbstractExtension
{
    private SearchFormGenerator $searchFormGenerator;

    /**
     * MyTwigExtension constructor.
     * @param SearchFormGenerator $searchFormGenerator
     */
    public function __construct(SearchFormGenerator $searchFormGenerator)
    {
        $this->searchFormGenerator = $searchFormGenerator;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getSearchForm', [$this->searchFormGenerator,'getSearchForm'])
        ];
    }

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
        $datebirth = new \DateTime($date->format('d-m-Y'));
        $now = new \DateTime();
        $age = $now->diff($datebirth);

        return $age->y;
    }
}