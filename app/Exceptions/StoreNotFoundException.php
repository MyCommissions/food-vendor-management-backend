<?php

namespace App\Exceptions;

use Exception;

class StoreNotFoundException extends Exception
{
    public function __construct($message = 'Store not found.')
    {
        parent::__construct($message, 404);
    }
}
