<?php

namespace Bronto\Transfer\Curl;

/**
 * Simple request builder implementation that builds
 * and completes a cURL request to a server.
 *
 * @author Philip Cali <philip.cali@bronto.com>
 */
class Request implements \Bronto\Transfer\Request
{
    private $_method;
    private $_uri;
    private $_options;
    private $_curl;
    private $_body;
    private $_headers = array();
    private $_params = array();
    private $_query = array();

    /**
     * Create a request builder with a method, uri, and options
     *
     * @param string $method
     * @param string $uri
     * @param \Bronto\Object $options
     * @param \Bronto\Resource\Proxy $curl
     */
    public function __construct($method, $uri, $options, $curl = null)
    {
        $this->_uri = $uri;
        $this->_method = $method;
        $this->_options = $options;
        if (is_null($curl)) {
            $curl = new \Bronto\Resource\Proxy("curl_");
        }
        $this->_curl = $curl;
    }

    /**
     * @see parent
     */
    public function header($name, $value)
    {
        $this->_headers[] = "$name: $value";
        return $this;
    }

    /**
     * @see parent
     */
    public function param($name, $value)
    {
        $this->_params[$name] = $value;
        if (!empty($this->_body)) {
            $this->_body = null;
        }
        return $this;
    }

    /**
     * @see parent
     */
    public function query($name, $value)
    {
        $this->_query[$name] = $value;
        return $this;
    }

    /**
     * @see parent
     */
    public function body($data)
    {
        $this->_body = $data;
        if (!empty($this->_params)) {
            $this->_params = array();
        }
        return $this;
    }

    /**
     * Adds the query parameters URL encoded to the base URI
     *
     * @return string
     */
    protected function _createUri()
    {
        $suffix = '';
        if (!empty($this->_query)) {
            if (strpos($this->_uri, '?') === false) {
                $suffix .= '?';
            } else {
                $suffix .= '&';
            }
            $suffix .= http_build_query($this->_query);
        }
        return $this->_uri . $suffix;
    }

    /**
     * Prepares the cURL handle with all of the set options
     */
    protected function _prepareCurl()
    {
        $prefix = "CURLOPT_";
        $pattern = '/([a-z0-9])([A-Z])/';
        foreach ($this->_options->toArray() as $curlOpt => $value) {
            // Assume we got a camelcase without a prefix
            if (!preg_match("/^{$prefix}/", $curlOpt)) {
                $words = preg_replace($pattern, "$1_$2", $curlOpt);
                $curlOpt = $prefix . strtoupper($words);
            }
            if (defined($curlOpt)) {
                $this->_curl->setopt(constant($curlOpt), $value);
            }
        }
    }

    /**
     * Sends the cURL request and reads response
     *
     * @return array(string, array)
     */
    protected function _completeRequest()
    {
        $this->_curl->init($this->_createUri());
        $this->_prepareCurl();
        $this->_curl->setopt(CURLOPT_RETURNTRANSFER, true);
        $this->_curl->setopt(CURLOPT_HEADER, true);
        if ($this->_method != self::POST) {
            $this->_headers[] = "X-HTTP-Method-Override: {$this->_method}";
        }
        if (!empty($this->_headers)) {
            $this->_curl->setopt(CURLOPT_HTTPHEADER, $this->_headers);
        }
        if (!empty($this->_params) || !empty($this->_body)) {
            $this->_curl->setopt(CURLOPT_POSTFIELDS, empty($this->_body) ? $this->_params : $this->_body);
        }
        $results = $this->_curl->exec();
        $code = $this->_curl->errno();
        if ($code) {
            $message = $this->_curl->error();
            $this->_curl->close();
            throw new \Bronto\Transfer\Exception($message, $code, $this);
        }
        $info = $this->_curl->getinfo();
        $this->_curl->close();
        return array($results, $info);
    }

    /**
     * Parse the response headers from the response body
     *
     * @param string $results
     * @param array $info
     * @return array(string, array)
     */
    protected function _parseHeaders($results, $info)
    {
        $headers = substr($results, 0, $info['header_size']);
        $body = substr($results, $info['header_size']);
        $table = array();
        foreach (preg_split('/\r?\n/', $headers) as $header) {
            if (!preg_match('/\:\s+/', $header)) {
                continue;
            }
            list($name, $value) = preg_split("/\\:\\s+/", $header);
            $table[$name] = $value;
        }
        return array(trim($body), $table);
    }

    /**
     * @see parent
     */
    public function respond()
    {
        list($results, $info) = $this->_completeRequest();
        list($body, $headers) = $this->_parseHeaders($results, $info);
        return new Response($body, $headers, $info);
    }
}
