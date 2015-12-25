<?php

namespace Galmi\Xacml;


trait UtilsTrait
{
    /**
     * Convert spec characters (e.g. "-", "_") to camel case
     *
     * @param $str
     * @return string
     */
    protected function camelCase($str)
    {
        // non-alpha and non-numeric characters become spaces
        $str = preg_replace('/[^a-z0-9]+/i', ' ', $str);
        $str = trim($str);
        // uppercase the first character of each word
        $str = ucwords($str);
        $str = str_replace(" ", "", $str);

        return $str;
    }
}