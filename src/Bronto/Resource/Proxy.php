<?php

namespace Bronto\Resource;

use Bronto\Utils;

/**
 * A resource proxy is to "classify" certain PHP functions
 * to make them both Object Oriented and testable.
 *
 * @author Philip Cali <philip.cali@bronto.com>
 */
class Proxy
{
    protected $_resource;
    protected $_prefix;

    /**
     * Construct the resource proxy with a prefix
     *
     * @param string $prefix
     * @param resource $resource
     */
    public function __construct($prefix, $resource = null)
    {
        $this->_prefix = $prefix;
        $this->_resource = $resource;
    }

    /**
     * Gets the underlying PHP resource
     *
     * @return resource
     */
    public function getResource()
    {
        return $this->_resource;
    }

    /**
     * Get the underlying PHP function prefix
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->_prefix;
    }

    /**
     * Proxy PHP functions
     *
     * @param string $name
     * @param array $args
     * @return mixed
     */
    public function __call($name, $args)
    {
        $function = $this->_prefix . Utils::underscore($name);
        if (!function_exists($function)) {
            throw new \BadMethodCallException("Resource function {$function} does not exist.");
        }
        if (!is_null($this->_resource)) {
            array_unshift($args, $this->_resource);
        }
        $return = call_user_func_array($function, $args);
        if ($name == 'close') {
            unset($this->_resource);
            $this->_resource = null;
            return $this;
        }
        if (is_resource($return)) {
            $this->_resource = $return;
            return $this;
        }
        return $return;
    }

    /**
     * Set a cleanup to close the attached resource
     *
     * @see parent
     */
    public function __destruct()
    {
        if (!is_null($this->_resource)) {
            $this->close();
        }
    }
}
