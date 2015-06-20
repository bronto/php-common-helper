<?php

namespace Bronto\Logger\Handler;

use Bronto\Logger\LogHandler;
use Bronto\Logger\LogInterface;

/**
 * Console handler will output logs to the console.
 *
 * @author Philip Cali <philip.cali@bronto.com>
 */
class Console implements LogHandler
{
    private static $_translate = array(
        LogInterface::DEBUG => 'DEBUG',
        LogInterface::INFO => 'INFO',
        LogInterface::WARN => 'WARNING',
        LogInterface::ERROR => 'ERROR',
    );

    protected $_format;

    /**
     * Populate the console logger with a date format
     *
     * @param string $format
     */
    public function __construct($format = 'c')
    {
        $this->_format = $format;
    }

    /**
     * Write the log to the output buffer
     *
     * @param int $level
     * @param string $message
     * @param array $backtrace
     * @return void
     */
    public function write($level, $message, $backtrace)
    {
        echo $this->_prefix($level, $backtrace) . '- ' . $message . "\n";
    }

    /**
     * Format the log prefix with relavent infomation
     *
     * @param int $level
     * @param array $backtrace
     * @return string
     */
    protected function _prefix($level, $backtrace)
    {
        $fileAndLine = ' ';
        if (array_key_exists('class', $backtrace) && !empty($backtrace['class'])) {
            $fileAndLine = sprintf(' %s::%s:%d ',
                $backtrace['class'],
                $backtrace['function'],
                $backtrace['line']);
        }
        if ($fileAndLine == ' ' && array_key_exists('file', $backtrace)) {
            $paths = pathinfo($backtrace['file']);
            $fileAndLine = sprintf(' %s:%d ', $paths['basename'], $backtrace['line']);
        }
        return sprintf('%s %s%s',
            date($this->_format),
            self::$_translate[$level],
            $fileAndLine);
    }
}
