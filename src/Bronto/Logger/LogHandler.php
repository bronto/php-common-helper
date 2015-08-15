<?php

namespace Bronto\Logger;

/**
 * A log handler is more or less an appender that writes
 * to a given location with some appender specific
 * implementation (outside of potentially logging).
 *
 * @author Philip Cali <philip.cali@bronto.com>
 */
interface LogHandler
{
    /**
     * Writes the message at the given level
     * and an optional backtrace
     *
     * @param int $level
     * @param string $message
     * @param array $backtrace
     */
    public function write($level, $message, $backtrace);
}
