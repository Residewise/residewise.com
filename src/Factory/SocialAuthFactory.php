<?php

declare(strict_types = 1);

namespace App\Factory;

use App\Entity\SocialAuth;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

class SocialAuthFactory
{
    public function create(string $token, string $provider, null|User|UserInterface $owner): SocialAuth {
        $socialAuth = new SocialAuth();
        $socialAuth->setToken($token);
        $socialAuth->setProvider($provider);

        if ($owner !== null) {
            $socialAuth->setOwner($owner);
        }

        return $socialAuth;
    }
}
