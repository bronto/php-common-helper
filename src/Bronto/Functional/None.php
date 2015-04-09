<?php

namespace Bronto\Functional;

/**
 * The None type represents a "Nothing" container
 *
 * @author Philip Cali <philip.cali@bronto.com>
 */
class None extends Option
{
    /**
     * @return boolean
     */
    public function isDefined()
    {
        return false;
    }

    /**
     * @throws \BadMethodCallException
     */
    public function get()
    {
        throw new \BadMethodCallException('None->get() cannot not be invoked.');
    }
}
