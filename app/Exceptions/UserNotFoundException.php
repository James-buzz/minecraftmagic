<?php
declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class UserNotFoundException extends Exception
{
    public function __construct(string $userId)
    {
        parent::__construct("User with ID {$userId} not found.");
    }
}
