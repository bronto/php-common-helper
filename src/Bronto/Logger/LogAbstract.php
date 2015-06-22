<?php

namespace Bronto\Logger;

/**
 * Abstract logger performing common entries
 *
 * @author Philip Cali <philip.cali@bronto.com>
 */
abstract class LogAbstract implements LogInterface
{
    protected $_level;
    protected $_requestBacktrace;

    /**
     * Logs below the target level will be ignored
     * Optionally, backtrace will be provided to handlers
     *
     * @param int $targetLevel
     * @param boolean $backtrace
     */
    public function __construct($targetLevel = LogInterface::INFO, $backtrace = true)
    {
        $this->_level = $targetLevel;
        $this->_requestBacktrace = $backtrace;
    }

    /**
     * Log function comes in the form of:
     *
     * @param int $level Log level of the logger
     * @param string $message The actual log message
     * @param varargs $args one to many arguments for string replacement
     * @return void
     */
    public function log()
    {
        $args = func_get_args();
        if (empty($args)) {
            return;
        }
        $level = array_shift($args);
        if (!is_int($level)) {
            throw new \InvalidArgumentException("First parameter to " . __METHOD__ . " is supposed to be a level, but got {$level}.");
        }
        if ($level < $this->_level) {
            return;
        }
        $message = array_shift($args);
        if (is_array($message)) {
            $args = $message;
            $message = array_shift($args);
        }
        if (!is_string($message)) {
            throw new \InvalidArgumentException("Second parameter to " . __METHOD__ . " is supposed to be a string message, but got {$message}.");
        }
        $appended = '';
        foreach ($args as $remaining) {
            if ($remaining instanceof \Exception) {
                $appended .= $remaining->getTraceAsString();
            } else {
                $message = preg_replace('/\{\}/', (string) $remaining, $message, 1);
            }
        }
        if ($this->_requestBacktrace) {
            $backtrace = array_slice(debug_backtrace(), 1);
            $fileInfo = array('class' => '');
            if (count($backtrace) > 1) {
                $fileInfo = array_shift($backtrace);
            }
            $backtrace = array_shift($backtrace) + $fileInfo;
            if (empty($fileInfo['class'])) {
                unset($backtrace['class']);
            }
        } else {
            $backtrace = array();
        }
        $this->_log($level, $message . $appended, $backtrace);
    }

    /**
     * see @parent
     */
    public function debug()
    {
        $this->log(self::DEBUG, func_get_args());
    }

    /**
     * see @parent
     */
    public function info()
    {
        $this->log(self::INFO, func_get_args());
    }

    /**
     * see @parent
     */
    public function warn()
    {
        $this->log(self::WARN, func_get_args());
    }

    /**
     * see @parent
     */
    public function error()
    {
        $this->log(self::ERROR, func_get_args());
    }

    /**
     * Implementors will handle the specific message here
     *
     * @param int $level
     * @param string $message
     * @param array $backtrace
     */
    protected abstract function _log($level, $message, $backtrace);
}
