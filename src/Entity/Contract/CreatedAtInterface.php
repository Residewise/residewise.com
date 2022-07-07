<?php

declare(strict_types = 1);

namespace App\Entity\Contract;

use DateTimeImmutable;

interface CreatedAtInterface
{
    public function getCreatedAt(): ?DateTimeImmutable;
}
