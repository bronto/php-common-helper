<?php

namespace Bronto\Functional;

/**
 * Test cases for Option
 *
 * @author Philip Cali <philip.cali@bronto.com>
 */
class OptionTest extends \PHPUnit_Framework_TestCase
{
    private $some;
    private $none;

    /**
     * @see parent
     */
    protected function setUp()
    {
        $this->some = new Some('Thing');
        $this->none = new None();
    }

    /**
     * @test
     * @group pmc
     */
    public function testIsDefined()
    {
        $this->assertTrue($this->some->isDefined());
        $this->assertFalse($this->none->isDefined());
    }

    /**
     * @test
     * @group pmc
     */
    public function testIsEmpty()
    {
        $this->assertFalse($this->some->isEmpty());
        $this->assertTrue($this->none->isEmpty());
    }

    /**
     * @test
     * @group pmc
     */
    public function testSomeGet()
    {
        $this->assertEquals('Thing', $this->some->get());
    }

    /**
     * @test
     * @group pmc
     * @expectedException BadMethodCallException
     */
    public function testNoneGetException()
    {
        $this->none->get();
    }

    /**
     * @test
     * @group pmc
     */
    public function testFilter()
    {
        $filtered = $this->some->filter(function($value) {
            return strlen($value) > 6;
        });
        $this->assertTrue($filtered->isEmpty());
        $this->assertTrue($this->some->filter(function($value) {
            return true;
        })->isDefined());
    }

    /**
     * @test
     * @group pmc
     */
    public function testMap()
    {
        $mapped = $this->some->map(function($value) {
            return strlen($value);
        });
        $this->assertEquals(5, $mapped->get());
    }

    /**
     * @test
     * @group pmc
     */
    public function testGetOrElse()
    {
        $this->assertEquals('Thing', $this->some->get());
        $this->assertEquals('Thing', $this->none->getOrElse('Thing'));
    }

    /**
     * @test
     * @group pmc
     */
    public function testOrElse()
    {
        $function = function() {
            return 'Thang';
        };
        $this->assertEquals('Thing', $this->some->orElse($function)->get());
        $this->assertEquals('Thang', $this->none->orElse($function)->get());
    }

    /**
     * @test
     * @group pmc
     * @expectedException RuntimeException
     */
    public function testEach()
    {
        $this->some->each(function() {
            throw new \RuntimeException("Made it!");
        });
    }
}
