<?php

declare(strict_types=1);

namespace App\Entity\Contract;

interface PriceableInterface
{
  public function setFee(string $fee): PriceableInterface;
  public function setCurrency(string $fee): PriceableInterface;
}
