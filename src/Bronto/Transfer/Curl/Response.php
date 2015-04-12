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
    private $_code;

    /**
     * Everything needed to read results
     *
     * @param string $results
     * @param array $headers
     * @param int $code
     */
    public function __construct($results, $headers, $code)
    {
        $this->_results = $results;
        $this->_headers = $headers;
        $this->_code = $code;
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
        return $this->_code;
    }
}
