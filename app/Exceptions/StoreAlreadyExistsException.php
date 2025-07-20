<?php

namespace App\Exceptions;

use Exception;

class StoreAlreadyExistsException extends Exception
{
    public function __construct($message = 'Vendor already has a store registered.')
    {
        parent::__construct($message, 400);
    }
}
