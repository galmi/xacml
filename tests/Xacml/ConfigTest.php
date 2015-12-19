<?php

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    public function testExistedKey()
    {
        $key = 'test_key';
        $value = 'test';

        \Galmi\Xacml\Config::set($key, $value);

        $this->assertEquals($value, \Galmi\Xacml\Config::get($key));
    }

    public function testMissingKey()
    {
        try {
            \Galmi\Xacml\Config::get('test2');
        } catch (\Exception $e) {
            $this->assertEquals("Key test2 not exists.", $e->getMessage());
        }
    }
}