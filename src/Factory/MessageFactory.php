<?php

namespace App\Factory;

use App\Entity\Message;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

class MessageFactory
{

    public function create(string $content, null|User|UserInterface $owner) : Message
    {
         return new Message(
            content: $content,
            owner: $owner
        );
    }

}
