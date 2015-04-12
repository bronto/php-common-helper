<?php

namespace Bronto\Serialize;

/**
 * Interface that defines a decoder
 *
 * @author Philip Cali <philip.cali@bronto.com>
 */
interface Decode
{
    /**
     * Decodes a string into an associative state
     *
     * @param string $input
     * @return mixed
     */
    public function decode($input);
}
