<?php

namespace Bronto;

class UtilsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @group pmc
     */
    public function testUnderscore()
    {
        $name = "someCamelCase";
        $expected = "some_camel_case";
        $this->assertEquals($expected, \Bronto\Utils::underscore($name));
    }

    /**
     * @test
     * @group pmc
     */
    public function testNormalize()
    {
        $name = "Test Field!";
        $expected = "test_field";
        $this->assertEquals($expected, \Bronto\Utils::normalize($name));
    }

    /**
     * @test
     * @group pmc
     */
    public function testPluralize()
    {
        $name = "test";
        $expected = "tests";
        $this->assertEquals($expected, \Bronto\Utils::pluralize($name));

        $name = "delivery";
        $expected = "deliveries";
        $this->assertEquals($expected, \Bronto\Utils::pluralize($name));
    }
    
    /**
     * @test
     * @group pmc
     * @dataProvider stringifyDataProvider
     */
    public function testStringify($testInput, $expectedOutput)
    {
    	$testOutput = \Bronto\Utils::stringify($testInput);
    	$this->assertEquals($expectedOutput, $testOutput);
    }
    
    public function stringifyDataProvider()
    {
    	$someObject = new \DateTime();
    	return [
    		["hello_world", "hello_world"],
    		[false, "false (boolean)"],
    		[37, "37"],
    		[["hello", "world"], print_r(["hello", "world"], true)],
    		[$someObject, print_r($someObject, true)]
    	];
    }
    
}
