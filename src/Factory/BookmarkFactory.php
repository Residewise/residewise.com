<?php

declare(strict_types = 1);

namespace App\Factory;

use App\Entity\Asset;
use App\Entity\Bookmark;
use App\Entity\User;

class BookmarkFactory
{
    public function create(Asset $asset, null|User $owner): Bookmark {
        $bookmark = new Bookmark();
        $bookmark->setAsset($asset);
        $bookmark->setOwner($owner);

        return $bookmark;
    }
}
