<?php

namespace Bronto\Functional;

/**
 * Container type that implements Monadic operations
 *
 * @author Philip Cali <philip.cali@bronto.com>
 */
abstract class Option implements Monadic
{
    /**
     * Whether or not the container contains a value
     *
     * @return boolean
     */
    public abstract function isDefined();

    /**
     * Retrieves the contained value
     *
     * @return mixed
     */
    public abstract function get();

    /**
     * Whether or not the container is empty
     *
     * @return boolean
     */
    public function isEmpty()
    {
        return !$this->isDefined();
    }

    /**
     * @see parent
     * @param callable $function
     * @return Monadic
     */
    public function each(callable $function)
    {
        if ($this->isDefined()) {
            $function($this->get());
        }
        return $this;
    }

    /**
     * @see parent
     * @param callable $function
     * @return Monadic
     */
    public function filter(callable $function)
    {
        if ($this->isDefined() && $function($this->get())) {
            return $this;
        }
        return new None();
    }

    /**
     * @see parent
     * @param callable $function
     * @return Monadic
     */
    public function map(callable $function)
    {
        if ($this->isDefined()) {
            return new Some($function($this->get()));
        }
        return $this;
    }

    /**
     * Gets the contained value or a defined default
     *
     * @param mixed $default
     * @return mixed
     */
    public function getOrElse($default)
    {
        if ($this->isDefined()) {
            return $this->get();
        }
        return $default;
    }

    /**
     * Convert this potentially empty container into a full container
     *
     * @param callable $function
     * @return Some
     */
    public function orElse(callable $function)
    {
        if ($this->isEmpty()) {
            return new Some($function());
        }
        return $this;
    }
}
