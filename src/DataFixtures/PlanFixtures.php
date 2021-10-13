<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Plan;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PlanFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $freePlan = $this->createFreePlan();
        $manager->persist($freePlan);

        $premiumPlan = $this->createPremiumPlan();
        $manager->persist($premiumPlan);

        $manager->flush();
    }

    private function createPremiumPlan(): Plan
    {
        $plan = new Plan();
        $plan->setTitle('premium');
        $plan->setFee(1000);

        return $plan;
    }

    private function createFreePlan(): Plan
    {
        $plan = new Plan();
        $plan->setTitle('free');
        $plan->setFee(0);

        return $plan;
    }
}
