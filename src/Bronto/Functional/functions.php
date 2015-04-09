<?php

namespace Bronto\Functional;

/**
 * Package level convenience for creating the some option
 *
 * @param mixed $value
 * @retun Some
 */
function some($value)
{
    return new Some($value);
}

/**
 * Package level convenience for creating the none type
 *
 * @return None
 */
function none()
{
    return new None();
}
