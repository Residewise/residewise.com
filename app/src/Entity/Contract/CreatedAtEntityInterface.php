<?php

declare(strict_types=1);

namespace App\Entity\Contract;

use DateTimeImmutable;

interface CreatedAtEntityInterface
{
  public function setCreatedAt(DateTimeImmutable $createdAt): self;
}
