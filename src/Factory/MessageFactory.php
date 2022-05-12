<?php

namespace App\Factory;

use App\Entity\Message;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

class MessageFactory
{

    public function create(string $content, null|User|UserInterface $owner) : Message
    {
         $message = new Message();
         $message->setContent($content);
         $message->setOwner($owner);
         return $message;
    }

}
