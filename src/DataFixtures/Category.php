<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category as CategoryEntity;
use App\Entity\SubCategory as SubCategoryEntity;

class Category extends Fixture
{

    const CATEGORIES = [
        'Vacations' => [
            'Travel',
            'Hotel',
            'Camping'
        ],
        'Job' => [
            'Job',
            'Internship',
            'Freelance'
        ],
        'Vehicle' => [
            'Car',
            'Motorbike',
            'Boat'
        ],
        'Property' => [
            'House',
            'Apartment',
            'Parking'
        ],
        'Fashion' => [
            'Clothing',
            'Shoes',
            'Accessories'
        ],
        'Home' => [
            'Kitchen',
            'Bathroom',
            'Furniture'
        ],
        'Multimedia' => [
            'Audio',
            'Video',
            'Photo'
        ],
        'Hobbies' => [
            'Sport',
            'Music',
            'Book'
        ],
        'Animals' => [
            'Pet',
            'Wild',
            'Domestic'
        ],
        'Professional equipment' => [
            'Tool',
            'Computer',
            'Phone'
        ],
        'Services' => [
            'Cleaning',
            'Cooking',
            'Carpentry'
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::CATEGORIES as $categoryName => $subCategoriesName) {
            $category = new CategoryEntity();
            $category->setName($categoryName);
            $manager->persist($category);

            foreach ($subCategoriesName as $subCategoryName) {
                $subCategory = new SubCategoryEntity();
                $subCategory->setName($subCategoryName);
                $subCategory->setCategory($category);
                $manager->persist($subCategory);
            }
        }
        $manager->flush();
    }
}
