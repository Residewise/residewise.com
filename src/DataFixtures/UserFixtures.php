<?php

namespace App\DataFixtures;

use App\Entity\Blog;
use App\Entity\User;
use App\Security\TokenGenerator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserFixtures extends Fixture
{

    private $faker;

    public function __construct(
        private UserPasswordHasherInterface $hasher,
        private TokenGenerator $tokenGenerator
    ) {
        $this->faker =  Factory::create();
    }

    public function load(ObjectManager $manager)
    {

        for ($i = 0; $i < 50; $i++) {
            $user = new User();
            $user->setFirstName($this->faker->firstName());
            $user->setLastName($this->faker->lastName());
            $user->setEmail($this->faker->email());
            $user->setToken($this->tokenGenerator->generateToken());
            $password = $this->hasher->hashPassword($user, '12345678');
            $user->setPassword($password);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
