<?php

namespace Bronto\Functional;

/**
 * Simple monadic interface that provides filtering,
 * transform, and iterative capabilities
 *
 * @author Philip Cali <philip.cali@bronto.com>
 */
interface Monadic
{
    /**
     * If the contained type is wrapping something, invoke the function
     *
     * @param callable $function
     * @return Monadic
     */
    public function each($function);

    /**
     * Run a filter function on the contained type, to produce another
     * contained Monadic type
     *
     * @param callable $function
     * @return Monadic
     */
    public function filter($function);

    /**
     * Run a transform on the contained type, to produce another
     * contained Monadic type
     *
     * @param callable $function
     * @return Monadic
     */
    public function map($function);
}
