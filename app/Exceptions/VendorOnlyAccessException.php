<?php

namespace App\Exceptions;

use Exception;

class VendorOnlyAccessException extends Exception
{
    public function __construct($message = 'Only vendors are allowed to perform this action.')
    {
        parent::__construct($message, 403);
    }
}
