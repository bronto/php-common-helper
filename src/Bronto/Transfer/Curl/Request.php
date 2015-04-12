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
    private $_headers = array();
    private $_params = array();
    private $_query = array();

    /**
     * Create a request builder with a method, uri, and options
     *
     * @param string $method
     * @param string $uri
     * @param \Bronto\Object $options
     */
    public function __construct($method, $uri, $options)
    {
        $this->_uri = $uri;
        $this->_method = $method;
        $this->_options = $options;
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
     * Adds the query parameters URL encoded to the base URI
     *
     * @return string
     */
    protected function _createUri()
    {
        $suffix = '';
        if (!empty($this->_query)) {
            if (preg_match('/\?/', $this->_uri)) {
                $suffix .= '?';
            } else {
                $suffix .= '&';
            }
            $suffix = http_build_query($this->_query);
        }
        return $this->_uri . $suffix;
    }

    /**
     * Prepares the cURL handle with all of the set options
     *
     * @param handle $ch
     */
    protected function _prepareCurl($ch)
    {
        $prefix = "CURLOPT_";
        $pattern = '/([a-z0-9])([A-Z])/';
        foreach ($this->_options->toArray() as $curlOpt => $value) {
            // Assume we got a camelcase without a prefix
            if (!preg_match("/^{$prefix}/", $curlOpt)) {
                $words = preg_replace($pattern, "$1_$2", $curlOpt));
                $curlOpt = $prefix . strtoupper($words);
            }
            if (defined($curlOpt)) {
                curl_setopt($ch, constant($curlOpt), $value);
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
        $ch = curl_init($this->_createUri());
        $this->_prepareCurl($ch);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        if ($this->_method != self::POST) {
            $this->_headers[] = "X-HTTP-Method-Override: {$this->_method}";
        }
        if (!empty($this->_headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->_headers);
        }
        if (!empty($this->_params)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->_params);
        }
        $results = curl_exec($ch);
        $code = curl_errno($ch);
        if ($code) {
            $message = curl_error($ch);
            curl_close($ch);
            throw new \Bronto\Transfer\Exception($message, $code);
        }
        $info = curl_getinfo($ch);
        curl_close($ch);
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
        foreach (explode("\r\n", $headers) as $header) {
            list($name, $value) = preg_split("/\\:\\s+/", $header, 1);
            $table[$name] = $value;
        }
        return array($body, $table);
    }

    /**
     * @see parent
     */
    public function respond()
    {
        list($results, $info) = $this->_completeRequest();
        list($body, $headers) = $this->_parseHeaders($results, $info);
        return new Response($body, $headers, $info['http_code']);
    }
}
