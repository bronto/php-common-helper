<?php

namespace Bronto\Logger;

/**
 * A log collection contains multiple appenders to write
 * a single log message across multiple outputs.
 *
 * @author Philip Cali <philip.cali@bronto.com>
 */
class LogCollection extends LogAbstract implements \IteratorAggregate
{
    protected $_handlers = array();

    /**
     * Construct a collection with a target level and handlers
     *
     * @param int $targetLevel
     * @param boolean $backtrace
     * @param array $handlers
     */
    public function __construct($targetLevel = LogInterface::INFO, $backtrace = true, $handlers = array())
    {
        parent::__construct($targetLevel, $backtrace);
        $this->_handlers = $handlers;
    }

    /**
     * @see parent
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->_handlers);
    }

    /**
     * Adds an additional handler to this collection
     *
     * @param \Bronto\Logger\LogHandler $handler
     * @return \Bronto\Logger\LogCollection
     */
    public function addHandler(LogHandler $handler)
    {
        $this->_handlers[] = $handler;
        return $this;
    }

    /**
     * @see parent
     */
    protected function _log($level, $message, $backtrace)
    {
        foreach ($this as $handler) {
            $handler->write($level, $message, $backtrace);
        }
    }
}
