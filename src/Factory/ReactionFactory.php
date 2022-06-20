<?php

namespace App\Factory;

use App\Entity\Asset;
use App\Entity\Reaction;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

class ReactionFactory
{

    public function create(
        ?string $type,
        null|User|UserInterface $owner,
        null|Asset $asset
    ) : Reaction
    {
        $reaction = new Reaction();
        $reaction->setType($type);
        $reaction->setOwner($owner);
        $reaction->setAsset($asset);

        return $reaction;

    }

}
