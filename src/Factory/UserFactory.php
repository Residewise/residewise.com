<?php

declare(strict_types = 1);

namespace App\Factory;

use App\Entity\User;
use App\Service\AvatarService;

class UserFactory
{
    public final const PASSWORD_NOT_SET = 'PASSWORD_NOT_SET';

    public function __construct(
        private readonly AvatarService $avatarService
    ) {
    }

    public function create(null|string $firstName, null|string $lastName, null|string $email): User {
        $user = new User();
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setEmail($email);
        $user->setAvatar($this->avatarService->createAvatar($email));
        $user->setPassword(self::PASSWORD_NOT_SET);

        return $user;
    }
}