<?php

namespace App\Entity\Contract;

use DateTimeImmutable;

interface CreatedAtInterface
{
    public function getCreatedAt() : ?DateTimeImmutable;
}
