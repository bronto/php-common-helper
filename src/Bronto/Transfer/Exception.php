<?php

namespace Bronto\Transfer;

/**
 * Transfer related exceptions. May or may not
 * contain information about the request
 *
 * @author Philip Cali <philip.cali@bronto.com>
 */
class Exception extends \RuntimeException
{
    /**
     * @see parent
     */
    public function __construct($message, $code)
    {
        parent::__construct($message, $code);
    }
}
