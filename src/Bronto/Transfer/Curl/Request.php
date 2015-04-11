<?php

namespace Bronto\Transfer\Curl;

class Request implements \Bronto\Transfer\Request
{
    private $_method;
    private $_uri;
    private $_options;
    private $_headers = array();
    private $_params = array();
    private $_query = array();

    public function __construct($method, $uri, $options)
    {
        $this->_method = $method;
        $this->_uri = $uri;
        $this->_options = $options;
    }

    public function header($name, $value)
    {
        $this->_headers[] = "$name: $value";
        return $this;
    }

    public function param($name, $value)
    {
        $this->_params[$name] = $value;
        return $this;
    }

    public function query($name, $value)
    {
        $this->_query[$name] = $value;
        return $this;
    }

    protected function _createUri()
    {
        $suffix = '';
        if (!empty($this->_query)) {
            $suffix = '?' . http_build_query($this->_query);
        }
        return $this->_uri . $suffix;
    }

    protected function _prepareCurl($ch)
    {
        $prefix = "CURLOPT_";
        foreach ($this->_options->toArray() as $curlOpt => $value) {
            $words = preg_split("/[A-Z]/", $curlOpt);
            $constant = $prefix . strtoupper(implode('_', $words));
            if (defined($constant)) {
                curl_setopt($ch, constant($constant), $value);
            }
        }
    }

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

    public function respond()
    {
        list($results, $info) = $this->_completeRequest();
        list($body, $headers) = $this->_parseHeaders($results, $info);
        return new Response($body, $headers, $info['http_code']);
    }
}
