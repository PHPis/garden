<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    /** @var Generator */
    protected Generator $faker;

    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create();
        $count = 20;

        for ($i = 0; $i <= $count; $i++) {
            $user = new User();
            $user->setFirstName($this->faker->firstName);
            $user->setLastName($this->faker->lastName);
            $user->setPassportNumber($this->faker->numberBetween(100000,999999));
            $manager->persist($user);
        }

        $manager->flush();
    }
}
