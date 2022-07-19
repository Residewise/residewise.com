<?php

namespace App\Exception;

use Exception;

class AssetException extends Exception
{
    public const RELATION_EMPTY = 'asset: %s empty relation';
}