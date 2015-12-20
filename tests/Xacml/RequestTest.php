<?php

class RequestTest extends PHPUnit_Framework_TestCase
{

    public function testGet()
    {
        $resource = [
            'id' => 1,
            'role' => 'Manager'
        ];
        $request = new \Galmi\Xacml\Request([
            'Resource' => $resource
        ]);

        $this->assertEquals($resource, $request->get('Resource'));
        $this->assertEmpty($request->get('Environment'));
    }

    public function testSet()
    {
        $resource = [
            'id' => 1,
            'role' => 'Manager'
        ];
        $request = new \Galmi\Xacml\Request();
        $request->set('Resource', $resource);
        $this->assertEquals($resource, $request->get('Resource'));
    }
}
