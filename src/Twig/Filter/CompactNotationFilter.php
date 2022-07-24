<?php

declare(strict_types = 1);

namespace App\Twig\Filter;

use NumberFormatter;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class CompactNotationFilter extends AbstractExtension
{
    public function getFilters()
    {
        return [new TwigFilter('compact_notation', fn(int $number): string => $this->formatInt($number))];
    }

    // output short int notation: 1,000 = 1.0k / 100,500 = 100.5k
    public function formatInt(int $number): string
    {
        $nf = new NumberFormatter('en_US', NumberFormatter::PADDING_POSITION);
        $nf->setAttribute(NumberFormatter::MIN_FRACTION_DIGITS, 0);
        $nf->setAttribute(NumberFormatter::MAX_FRACTION_DIGITS, 1);

        return $nf->format($number);
    }
}
