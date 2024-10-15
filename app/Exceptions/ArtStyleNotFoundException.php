<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class ArtStyleNotFoundException extends Exception
{
    public function __construct(string $artTypeId, string $artStyleId)
    {
        parent::__construct("Art style with identifier '$artStyleId' not found for art type with identifier '$artTypeId'");
    }
}
