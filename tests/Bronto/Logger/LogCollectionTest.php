<?php

namespace Bronto\Logger;

class LogCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @group pmc
     */
    public function testLog()
    {
        $mockHandler = $this
            ->getMockBuilder('\Bronto\Logger\LogHandler')
            ->setMethods(array('write'))
            ->getMock();

        $collection = new LogCollection();
        $collection->addHandler($mockHandler);

        $mockHandler->expects($this->once())
            ->method('write')
            ->with(
                $this->equalTo(LogInterface::ERROR),
                $this->equalTo('Found something at 110')
            );
        $collection->error('Found {} at {}', 'something', 110);
    }

    /**
     * @test
     * @group pmc
     */
    public function testBacktrace()
    {
        $mockHandler = $this
            ->getMockBuilder('\Bronto\Logger\LogHandler')
            ->setMethods(array('write'))
            ->getMock();
        $collection = new LogCollection(LogInterface::INFO, true);
        $collection->addHandler($mockHandler);

        $mockHandler->expects($this->once())
            ->method('write')
            ->with(
                $this->equalTo(LogInterface::INFO),
                $this->equalTo('Found something at 110'),
                $this->equalTo(array(
                    'function' => 'testBacktrace',
                    'class' => 'Bronto\Logger\LogCollectionTest',
                    'object' => $this,
                    'type' => '->',
                    'args' => array(),
                    'file' => __FILE__,
                    'line' => 58
                ))
            );
        $collection->info('Found {} at {}', 'something', 110);
    }
}
