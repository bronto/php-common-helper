<?php

namespace Bronto\Transfer;

class HeadersTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @group pmc
     */
    public function testAutoLoaded()
    {
        $this->assertEquals('Accept', Headers::ACCEPT);
        $this->assertEquals('application/json', Headers::APPLICATION_JSON);
    }
}
