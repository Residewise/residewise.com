<?php

declare(strict_types = 1);

namespace App\Entity\Contract;

interface PriceableEntityInterface
{

    public function getPrice(): ?string;

    public function setPrice(string $price): self;

    public function getCurrency(): ?string;

    public function setCurrency(string $currency): self;
}