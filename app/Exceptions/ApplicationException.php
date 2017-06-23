<?php

namespace App\Exceptions;

class ApplicationException extends \Exception
{
    public function __construct($code = 0, $message = '')
    {
        parent::__construct($message, $code);
    }
}
