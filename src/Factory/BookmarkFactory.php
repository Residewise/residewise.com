<?php

declare(strict_types = 1);

namespace App\Factory;

use App\Entity\Asset;
use App\Entity\Bookmark;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

class BookmarkFactory
{
    public function create(?Asset $asset, null|User|UserInterface $owner): Bookmark {
        $bookmark = new Bookmark();
        $bookmark->setAsset($asset);
        $bookmark->setOwner($owner);

        return $bookmark;
    }
}
