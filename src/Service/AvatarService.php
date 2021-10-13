<?php

namespace App\Service;

use Hedronium\Avity\Avity;
use Hedronium\Avity\Generators\Hash;
use Hedronium\Avity\Layouts\DiagonalMirror;
use Symfony\Component\Security\Core\User\UserInterface;
use function base64_encode;

final class AvatarService
{

    public function createAvatar(String $hashString) : string
    {
        $avity = Avity::init([
            'generator' => Hash::class,
            'layout' => DiagonalMirror::class
        ]);

        $avity->hash($hashString);
        $avity->padding(50);
        $avity->width(300);
        $avity->height(300);
        $avity->style()->background(255,255,255);
        $avity->style()->variedColor()->spacing(10);

        return 'data:jpg;base64,' . base64_encode($avity->generate()->jpg()->__toString());
    }

}
