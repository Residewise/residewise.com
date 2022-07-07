<?php

declare(strict_types = 1);

namespace App\Factory;

use App\Entity\Asset;
use App\Entity\AssetView;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

class AssetViewFactory
{
    public function create(?Asset $asset, null|User|UserInterface $owner): AssetView
    {
        $assetView = new AssetView();
        $assetView->setAsset($asset);
        $assetView->setOwner($owner);

        return $assetView;
    }
}
