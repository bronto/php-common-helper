<?php

namespace Bronto\StandardResource;

/**
 * Test cases for the resource Proxy
 *
 * @author Philip Cali <philip.cali@bronto.com>
 */
class ProxyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @group pmc
     */
    public function testImplicitResourceCreation()
    {
        $resource = new Proxy("f");
        $resource->open('php://stdout', 'w');
        $this->assertTrue(is_resource($resource->getResource()));
    }

    /**
     * @test
     * @group pmc
     */
    public function testPHPFileFunctions()
    {
        $str = 'Hello World!';
        $resource = new Proxy("f");
        $resource->open('php://temp', 'w');
        $bytes = $resource->write($str);
        $this->assertEquals(strlen($str), $bytes);
    }

    /**
     * @test
     * @group pmc
     * @expectedException \BadMethodCallException
     */
    public function testUnknownFunctions()
    {
        $resource = new Proxy("f");
        $resource->unknown();
    }
}
