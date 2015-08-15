<?php

namespace Bronto\Serialize\Json;

/**
 * Test cases for the Standard serializer
 *
 * @author Philip Cali <philip.cali@bronto.com>
 */
class StandardTest extends \PHPUnit_Framework_TestCase
{
    private $_json;

    /**
     * @see parent
     */
    protected function setUp()
    {
        $this->_json = new Standard();
    }

    /**
     * @test
     * @group pmc
     */
    public function testMimeType()
    {
        $this->assertEquals('application/json', $this->_json->getMimeType());
    }

    /**
     * @test
     * @group pmc
     */
    public function testEncode()
    {
        $json = array('name' => 'Philip Cali', 'age' => 99);
        $expected = '{"name":"Philip Cali","age":99}';
        $this->assertEquals($expected, $this->_json->encode($json));
    }

    /**
     * @test
     * @group pmc
     */
    public function testDecode()
    {
        $json = '{"name":"Philip Cali","age":99}';
        $expected = array('name' => 'Philip Cali', 'age' => 99);
        $this->assertEquals($expected, $this->_json->decode($json));
    }

    /**
     * @test
     * @group pmc
     */
    public function testEncodeException()
    {
        $text = "\xB1\x31";
        try {
            $this->_json->encode($text);
            $this->fail('Should have thrown exception.');
        } catch (\Bronto\Serialize\Exception $e) {
            $this->assertTrue($e->isEncoding());
            $this->assertEquals($text, $e->getThing());
            $this->assertEquals(JSON_ERROR_UTF8, $e->getCode());
            $this->assertEquals('Malformed UTF-8 characters', $e->getMessage());
        }
    }

    /**
     * @test
     * @group pmc
     */
    public function testDecodeException()
    {
        $text = '{"name":"Philip Cali",';
        try {
            $this->_json->decode($text);
            $this->fail('Should have thrown exception.');
        } catch (\Bronto\Serialize\Exception $e) {
            $this->assertFalse($e->isEncoding());
            $this->assertEquals($text, $e->getInput());
            $this->assertEquals(JSON_ERROR_SYNTAX, $e->getCode());
            $this->assertEquals('Syntax error, malformed JSON', $e->getMessage());
        }
    }
}
