<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserEnabledChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (! $user instanceof User) {
            return;
        }

        if (! $user->getIsEnabled()) {
            throw new DisabledException();
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
    }
}
