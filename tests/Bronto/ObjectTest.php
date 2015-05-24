<?php

namespace Bronto;

/**
 * Test case for Object
 *
 * @author Philip Cali <philip.cali@bronto.com>
 */
class ObjectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @group pmc
     */
    public function testMagicGet()
    {
        $expected = array(
            'firstName' => 'Philip',
            'lastName' => 'Cali',
            'age' => 99.99
        );
        $object = new Object($expected);

        $this->assertEquals($expected['firstName'], $object->getFirstName());
        $this->assertEquals($expected['lastName'], $object->getLastName());
        $this->assertEquals($expected['age'], $object->getAge());
    }

    /**
     * @test
     * @group pmc
     */
    public function testMagicHas()
    {
        $object = new Object(array('firstName' => 'Philip'));
        $this->assertFalse($object->hasLastName());
        $this->assertTrue($object->hasFirstName());
    }

    /**
     * @test
     * @group pmc
     */
    public function testMagicUnset()
    {
        $object = new Object(array('firstName' => 'Philip'));
        $this->assertEquals('Philip', $object->getFirstName());
        $object->unsetFirstName();
        $this->assertFalse($object->hasFirstName());
    }

    /**
     * @test
     * @group pmc
     */
    public function testMagicSet()
    {
        $object = new Object();
        $object->setFirstName('Philip')->withLastName('Cali');
        $this->assertEquals('Philip', $object->getFirstName());
        $this->assertEquals('Cali', $object->getLastName());
    }

    /**
     * @test
     * @group pmc
     */
    public function testMagicSafe()
    {
        $object = new Object();
        $object
            ->withFirstName('Philip')
            ->withLastName('Cali')
            ->withAge(99.99);
        $safe = $object->safeTitle();
        $this->assertFalse($safe->isDefined());
        $this->assertTrue($object->safeFirstName()->isDefined());
    }

    /**
     * @test
     * @group pmc
     */
    public function testUnderscore()
    {
        $object = new Object(array(), true);
        $object
            ->withFirstName('Philip')
            ->withLastName('Cali')
            ->withAge(99.99);
        $expected = array('first_name' => 'Philip', 'last_name' => 'Cali', 'age' => 99.99);
        $this->assertEquals($expected, $object->toArray());
    }

    /**
     * @test
     * @group pmc
     */
    public function testMagicSetter()
    {
        $object = new Object();
        $object->id = 'abc123';
        $object->name = 'Blade';
        $expected = ['id' => 'abc123', 'name' => 'Blade'];
        $this->assertEquals($expected, $object->toArray());
    }

    /**
     * @test
     * @group pmc
     */
    public function testMagicGetter()
    {
        $object = new Object(['name' => 'Blade']);
        $this->assertEquals('Blade', $object->name);
    }
}
