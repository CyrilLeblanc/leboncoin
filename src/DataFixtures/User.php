<?php

namespace App\DataFixtures;

use App\Entity\Image;
use App\Entity\Category;
use App\Entity\Post as PostEntity;
use App\Entity\User as UserEntity;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Address as AddressEntity;
use Doctrine\Bundle\FixturesBundle\Fixture;

class User extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // remove all images before fetching them
        exec("rm " . __DIR__ . "/../../public/img/posts/*");
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
                    ->setCategory($faker->randomElement($manager->getRepository(Category::class)->findAll()));
                $manager->persist($post);
                $manager->flush();

                file_put_contents(__DIR__ . '/../../public/img/posts/' . $post->getId() . '-0.jpg', file_get_contents('https://api.lorem.space/image?w=300&h=300'));
                echo "$i/10\n";
                $image = (new Image())
                    ->setPost($post)
                    ->setRank(0)
                    ->setExtension('jpg');
                $manager->persist($image);
            }
        }

        $manager->flush();
    }
}
