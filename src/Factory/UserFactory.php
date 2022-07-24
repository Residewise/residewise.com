<?php

declare(strict_types = 1);

namespace App\Factory;

use App\Entity\SocialAuth;
use App\Entity\User;
use App\Service\AvatarService;

class UserFactory
{
    public final const PASSWORD_NOT_SET = 'PASSWORD_NOT_SET';

    public function __construct(
        private readonly AvatarService $avatarService
    ) {
    }

    public function create(string $firstName, string $lastName, string $email, ?SocialAuth $socialAuth): User {
        $user = new User();
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setEmail($email);
        $user->setAvatar($this->avatarService->createAvatar($email));
        $user->setPassword(self::PASSWORD_NOT_SET);

        if ($socialAuth !== null) {
            $user->addSocialAuth($socialAuth);
        }

        return $user;
    }
}
