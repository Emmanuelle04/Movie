<?php

// src/Twig/AppExtension.php
namespace App\Twig;

use App\Entity\Movie;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('getCategories', [$this, 'getCategoryFromGenres']),
        ];
    }

    /**
     * @param Movie $data
     * @return array
     */
    public function getCategoryFromGenres(Movie $data): array
    {
        // Get list of genres
        $genres = $data->getGenres();

        // Define array
        $categories = [];

        // Assign categories name to array
        foreach ($genres as $genre) {
            $categories[] = $genre->getCategoryId()->getName();
        }

        return $categories;
    }
}