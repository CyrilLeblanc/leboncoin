<?php

namespace App\DataFixtures;

use App\Entity\SubCategory;
use App\Entity\Post as PostEntity;
use App\Entity\User as UserEntity;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Address as AddressEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class User extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 10; $i++) {
            $address = (new AddressEntity())
                ->setPostcode($faker->postcode())
                ->setCity($faker->city())
                ->setNumber($faker->randomDigit(3))
                ->setStreet($faker->streetName());
            $manager->persist($address);

            $user = (new UserEntity())
                ->setEmail($faker->email())
                ->setRoles(['ROLE_USER'])
                ->setUsername($faker->name())
                ->setPhone($faker->phoneNumber)
                ->setAddress($address);
            $user->setPassword('password');
            $manager->persist($user);

            for($j = 0; $j < 10; $j++) {
                $post = (new PostEntity())
                    ->setTitle($faker->sentence(3))
                    ->setDetail($faker->paragraph(18))
                    ->setUser($user)
                    ->setPrice($faker->randomFloat(2, 0, 1000))
                    ->setPublicationDate($faker->dateTimeBetween('-1 years', 'now'))
                    ->setCategory($faker->randomElement($manager->getRepository(SubCategory::class)->findAll()));
                $manager->persist($post);
            }
        }

        $manager->flush();
    }
}
