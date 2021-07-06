<?php

declare(strict_types=1);

namespace App\Entity\Contract;

use App\Entity\PropertyOwner;

interface PropertyOwnerEntityInterface
{
  public function addPropertyOwner(PropertyOwner $property): PropertyOwnerEntityInterface;
}
