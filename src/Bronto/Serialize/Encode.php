<?php

namespace Bronto\Serialize;

/**
 * Interface that defines an encoder
 *
 * @author Philip Cali <philip.cali@bronto.com>
 */
interface Encode
{
    /**
     * Encodes some value into a string state
     *
     * @param mixed $thing
     * @return string
     */
    public function encode($thing);
}
