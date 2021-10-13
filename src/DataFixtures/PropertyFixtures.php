<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Property;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use function rand;

class PropertyFixtures extends Fixture
{
    private readonly mixed $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {


        $property = $this->createProperty();

        $manager->persist($property);
        $manager->flush();
    }

    public function createProperty(): Property
    {
        $loremIpsum = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus vel nunc vehicula, porta est et, dapibus arcu. Ut mi nulla, vehicula ac risus eget, finibus finibus nibh.';
        $property = new Property();
        $property->setTitle($this->faker->word());
        $property->setDescription($loremIpsum);
        $property->setType($this->faker->randomElement(Property::TYPES));
        $property->setFee(rand(9000, 35000));
        $property->setCurrency('CZK');
        $property->setTerm($this->faker->randomElement(Property::TERMS));
        $property->setLongitude(50.102391);
        $property->setLatitude(14.434457);
        $property->setAddress($this->faker->address);
        $property->setSqm($this->faker->randomNumber(2));

        return $property;
    }
}
