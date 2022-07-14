<?php

namespace App\Factory;

use App\Entity\ExchangeRate;

class ExchangeRateFactory
{

    public function create(
        string $currency,
        string $rate
    ) : ExchangeRate
    {
        $exchangerate = new ExchangeRate();
        $exchangerate->setCurrency($currency);
        $exchangerate->setRate($rate);

        return $exchangerate;
    }

}