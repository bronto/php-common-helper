<?php

namespace Bronto\Serialize;

/**
 * Interface that represents a complete bi-directional
 * serailizer with associated MIME type.
 *
 * @author Philip Cali <philip.cali@bronto.com>
 */
interface BiDirectional extends Encode, Decode
{
    /**
     * Gets the MIME type for this bi-directional serializer
     *
     * @return string
     */
    public function getMimeType();
}
