<?php

declare(strict_types = 1);

namespace App\Entity\Contract;

use Symfony\Component\Security\Core\User\UserInterface;

interface UserOwnedEntityInterface
{
    public function getOwner(): null|UserInterface;

    public function setOwner(null|UserInterface $owner): self;
}