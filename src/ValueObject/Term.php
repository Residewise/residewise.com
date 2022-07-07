<?php

declare(strict_types = 1);

namespace App\ValueObject;

enum Term: string
{
    case RENT = 'rent';
    case SALE = 'sale';

}
