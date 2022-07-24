<?php

declare(strict_types = 1);

namespace App\Exception;

use Exception;

class AssetException extends Exception
{
    public final const RELATION_EMPTY = 'asset: %s empty relation';
}