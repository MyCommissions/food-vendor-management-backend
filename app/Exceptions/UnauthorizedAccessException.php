<?php

namespace App\Exceptions;

use Exception;

class UnauthorizedAccessException extends Exception
{
    public function __construct($message = 'Unauthorized access.')
    {
        parent::__construct($message, 403);
    }
}
