<?php

namespace Bronto\Transfer\Curl;

/**
 * Implemented transfer adapter that handles cURL requests
 *
 * @author Philip Cali <philip.cali@bronto.com>
 */
class Adapter implements \Bronto\Transfer\Adapter
{
    protected $_options;

    /**
     * Set any additional cURL parameters in the option collection
     *
     * @param mixed $options
     */
    public function __construct($options = array())
    {
        if (is_array($options)) {
            $this->_options = new \Bronto\DataObject($options);
        } else {
            $this->_options = $options;
        }
    }

    /**
     * @see parent
     */
    public function createRequest($method, $uri)
    {
        return new Request($method, $uri, $this->_options);
    }
}
