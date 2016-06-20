<?php

namespace Bronto;

/**
 * Test case for DataObject
 *
 * @author Philip Cali <philip.cali@bronto.com>
 */
class DataObjectTest extends \PHPUnit_Framework_TestCase
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
        $object = new DataObject($expected);

        $this->assertEquals($expected['firstName'], $object->getFirstName());
        $this->assertEquals($expected['lastName'], $object->getLastName());
        $this->assertEquals($expected['age'], $object->getAge());
    }

    /**
     * @test
     * @group pmc
     */
    public function testIncrement()
    {
        $object = new DataObject(['total' => 1, 'success' => 1]);
        $object->incrementSuccess()->incrementTotal(3);
        $this->assertEquals(['total' => 4, 'success' => 2], $object->toArray());
    }

    /**
     * @test
     * @group pmc
     */
    public function testDecrement()
    {
        $object = new DataObject(['total' => 4, 'success' => 2]);
        $object->decrementTotal(3)->decrementSuccess();
        $this->assertEquals(['total' => 1, 'success' => 1], $object->toArray());
    }

    /**
     * @test
     * @group pmc
     */
    public function testMagicHas()
    {
        $object = new DataObject(array('firstName' => 'Philip'));
        $this->assertFalse($object->hasLastName());
        $this->assertTrue($object->hasFirstName());
    }

    /**
     * @test
     * @group pmc
     */
    public function testMagicUnset()
    {
        $object = new DataObject(array('firstName' => 'Philip'));
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
        $object = new DataObject();
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
        $object = new DataObject();
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
        $object = new DataObject(array(), true);
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
        $object = new DataObject();
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
        $object = new DataObject(['name' => 'Blade']);
        $this->assertEquals('Blade', $object->name);
    }
}
