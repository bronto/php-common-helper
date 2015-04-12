<?php

namespace Bronto\Transfer\Curl;

/**
 * Simple response object implementation that
 * reads results from the cURL response
 *
 * @author Philip Cali <philip.cali@bronto.com>
 */
class Response implements \Bronto\Transfer\Response
{
    private $_results;
    private $_headers;
    private $_info;

    /**
     * Everything needed to read results
     *
     * @param string $results
     * @param array $headers
     * @param array $info
     */
    public function __construct($results, $headers, $info)
    {
        $this->_results = $results;
        $this->_headers = $headers;
        $this->_info = new \Bronto\Object($info, true);
    }

    /**
     * @see parent
     */
    public function body()
    {
        return $this->_results;
    }

    /**
     * @see parent
     */
    public function header($name)
    {
        return $this->_headers[$name];
    }

    /**
     * @see parent
     */
    public function code()
    {
        return $this->_info->getHttpCode();
    }

    /**
     * Gets the cURL info for the transfer
     *
     * @return \Bronto\Object
     */
    public function info()
    {
        return $this->_info;
    }
}
