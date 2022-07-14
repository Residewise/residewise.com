<?php

namespace App\Entity\Contract;

use App\Entity\Person;

interface PersonOwnedEntityInterface
{
    public function getOwner() : null|Person;
    public function setOwner(null|Person $owner) : self;
}