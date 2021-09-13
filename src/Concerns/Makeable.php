<?php

namespace ChinLeung\Nuvei\Concerns;

trait Makeable
{
    /**
     * Create a new instance of the class.
     *
     * @return static
     */
    public static function make(...$args)
    {
        return new static(...$args);
    }
}
