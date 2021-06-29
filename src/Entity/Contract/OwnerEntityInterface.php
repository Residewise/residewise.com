<?php

declare(strict_types=1);

namespace App\Entity\Contract;

use Symfony\Component\Security\Core\User\UserInterface;

interface OwnerEntityInterface
{
    public function setOwner(UserInterface $user): self;
}
