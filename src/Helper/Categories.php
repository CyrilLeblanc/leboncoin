<?php

namespace App\Helper;

use App\Repository\CategoryRepository;

class Categories
{
    public function __construct(
        private CategoryRepository $categoryRepository
    ) {
    }

    public function getCategories(): array
    {
        $categories = [];
        foreach($this->categoryRepository->findAll() as $category) {
            $categories[$category->getId()] = $category->getSubCategories();
        }
        return $categories;
    }
}
