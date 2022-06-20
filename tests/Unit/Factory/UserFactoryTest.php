<?php

namespace App\Tests\Factory\Unit;

use App\Entity\User;
use App\Factory\UserFactory;
use App\Service\AvatarService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserFactoryTest extends WebTestCase
{
    public function testCreate() : void
    {
        $userFactory = $this->getMockBuilder(UserFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
        $user = $userFactory->create(
            'john',
            'doe',
            'joe.doe@unit-test.example',
            null,
        );

        $this->assertInstanceOf(User::class, $user);
    }
}