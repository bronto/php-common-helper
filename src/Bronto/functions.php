<?php

namespace Bronto;

use Bronto\Api\Options;

/**
 * Package level convenience for creating chainable objects
 *
 * @param array $data
 * @return Object
 */
function object(array $data = array())
{
    return new Object($data);
}

/**
 * Package level convenience for creating API options
 *
 * @param array $data
 * @return Options
 */
function options(array $data = array())
{
    return new Options($data);
}
