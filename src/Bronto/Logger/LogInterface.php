<?php

namespace Bronto\Logger;

/**
 * Logger interface bridge intended to be used like the
 * following:
 *
 * $logger->info("Found {} at {}", $something, $number);
 *
 * @author Philip Cali <philip.cali@bronto.com>
 */
interface LogInterface
{
    const DEBUG = 0;
    const INFO = 10;
    const WARN = 20;
    const ERROR = 30;

    /**
     * Write at the debug level
     *
     * @param string $message
     * @param varargs $args
     */
    public function debug();

    /**
     * Write at the info level
     *
     * @param string $message
     * @param varargs $args
     */
    public function info();

    /**
     * Write at the warning level
     *
     * @param string $message
     * @param varargs $args
     */
    public function warn();

    /**
     * Write at the error level
     *
     * @param string $message
     * @param varargs $args
     */
    public function error();
}
