<?php

namespace Bronto\Transfer;

class Exception extends \RuntimeException
{
    public function __construct($message, $code)
    {
        parent::__construct($message, $code);
    }
}
