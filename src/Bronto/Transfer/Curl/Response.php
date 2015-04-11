<?php

namespace Bronto\Transfer\Curl;

class Response implements \Bronto\Transfer\Response
{
    private $_results;
    private $_headers;
    private $_code;

    public function __construct($results, $headers, $code)
    {
        $this->_results = $results;
        $this->_headers = $headers;
        $this->_code = $code;
    }

    public function body()
    {
        return $this->_results;
    }

    public function header($name)
    {
        return $this->_headers[$name];
    }

    public function code()
    {
        return $this->_code;
    }
}
