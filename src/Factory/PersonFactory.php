<?php

namespace App\Factory;

use App\Entity\Person;
use App\Service\AvatarService;

class PersonFactory
{

    public function __construct(
        private readonly AvatarService $avatarService
    )
    {
    }

    public function create(
        string $firstName, string $lastName, string $email
    ): Person
    {
        $person = new Person();
        $person->setFirstName($firstName);
        $person->setLastName($lastName);
        $person->setEmail($email);
        $person->setAvatar($this->avatarService->createAvatar($email));

        return $person;
    }
}