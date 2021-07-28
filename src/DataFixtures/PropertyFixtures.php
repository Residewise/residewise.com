<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Property;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PropertyFixtures extends Fixture
{
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 50; ++$i) {
            $this->loadProperties($manager);
        }
    }

    public function loadProperties(ObjectManager $manager): void
    {
        $loremIpsum = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus vel nunc vehicula, porta est et, dapibus arcu. Ut mi nulla, vehicula ac risus eget, finibus finibus nibh.';
        $property = new Property();
        $property->setTitle($this->faker->word());
        $property->setDescription($loremIpsum);
        $property->setType($this->faker->randomElement(Property::TYPES));
        $property->setContract($this->faker->randomElement(Property::CONTRACTS));
        $property->setFee($this->faker->numberBetween(90, 1000));
        $property->setCurrency('CZK');
        $property->setTerm($this->faker->randomElement(Property::TERMS));
        $property->setPluscode('4C2M+XQ Prague, Czechia');
        $property->setLongitude(50.102391);
        $property->setLatitude(14.434457);
        $property->setSqm($this->faker->randomNumber(2));

        $manager->persist($property);
        $manager->flush();
    }
}
