<?php

declare(strict_types = 1);

namespace App\Factory;

use App\Entity\Asset;
use App\Entity\Reaction;
use App\Entity\User;

class ReactionFactory
{
    public function create(?string $type, null|User $owner, Asset $asset): Reaction
    {
        $reaction = new Reaction();
        $reaction->setType($type);
        $reaction->setOwner($owner);
        $reaction->setAsset($asset);

        return $reaction;

    }
}
