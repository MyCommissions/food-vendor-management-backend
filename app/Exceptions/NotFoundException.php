<?php

namespace App\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    public function __construct($message = 'Item Not Found.')
    {
        parent::__construct($message, 403);
    }
}
