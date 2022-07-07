<?php

declare(strict_types = 1);

namespace App\Entity\Contract;

interface PriceableEntityInterface
{
    public function getPrice(): ?int;

    public function setPrice(int $price): self;

    public function getCurrency(): ?string;

    public function setCurrency(string $currency): self;
}