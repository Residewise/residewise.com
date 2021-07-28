<?php

declare(strict_types=1);

namespace App\Entity\Contract;

use App\Entity\Property;

interface PropertyEntityInterface
{
    public function setProperty(Property $property): self;
}
