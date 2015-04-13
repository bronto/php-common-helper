<?php

namespace Bronto;

class Utils
{
    /**
     * Camel case to underscore conversion
     *
     * @param string $name
     * @return string
     */
    public static function underscore($name)
    {
        $pattern = '/([a-z0-9])([A-Z])/';
        $underscores = preg_replace($pattern, "$1_$2", $name);
        return strtolower($underscores);
    }
}
