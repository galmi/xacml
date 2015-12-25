<?php

namespace Galmi\Xacml;


class Config
{

    const ATTRIBUTE_FINDER = 'AttributeFinder';
    const FUNC_REGISTRY = 'FuncRegistry';
    const COMBINING_ALGORITHM_REGISTRY = 'CombiningAlgorithmRegistry';

    protected static $config = array();

    private function __construct() {}

    /**
     * @param string $key
     * @param mixed $value
     */
    public static function set($key, $value)
    {
        self::$config[$key] = $value;
    }

    /**
     * @param string $key
     * @return mixed
     * @throws \Exception
     */
    public static function get($key)
    {
        if (!isset(self::$config[$key])) {
            throw new \Exception("Key {$key} not exists.");
        }
        return self::$config[$key];
    }
}
