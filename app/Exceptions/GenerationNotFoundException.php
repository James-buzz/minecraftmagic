<?php
declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class GenerationNotFoundException extends Exception
{
    public function __construct(string $generationId)
    {
        parent::__construct("Generation with id '$generationId' not found");
    }
}
