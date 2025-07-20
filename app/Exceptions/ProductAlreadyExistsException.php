<?php

namespace App\Exceptions;

use Exception;

class ProductAlreadyExistsException extends Exception
{
    public function __construct($message = 'Product already exists for this store.')
    {
        parent::__construct($message, 403);
    }
}
