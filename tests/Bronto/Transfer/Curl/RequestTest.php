<?php

namespace Bronto\Transfer\Curl;

/**
 * Test cases for the cURL request builder
 *
 * @author Philip Cali <philip.cali@bronto.com>
 */
class RequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Mocks the curl calls to unit test the adapter
     *
     * @return \Bronto\Resource\Proxy
     */
    private function _mockCurl()
    {
        $curl = $this
            ->getMockBuilder('\Bronto\Resource\Proxy')
            ->setMethods(array(
                'init',
                'setopt',
                'exec',
                'errno',
                'error',
                'getinfo',
                'close'
            ))
            ->disableOriginalConstructor()
            ->getMock();
        return $curl;
    }

    /**
     * @test
     * @group pmc
     */
    public function testQueryParams()
    {
        $body = <<<RESPONSE
Server: Make-Believe
Content-Type: text/plain

Blade
RESPONSE;

        $curl = $this->_mockCurl();
        $curl->expects($this->once())
            ->method('init')
            ->with('http://google.com?q=this&q2=that');

        $curl->expects($this->exactly(3))
            ->method('setopt')
            ->withConsecutive(
                array($this->equalTo(CURLOPT_RETURNTRANSFER), $this->equalTo(true)),
                array($this->equalTo(CURLOPT_HEADER), $this->equalTo(true)),
                array($this->equalTo(CURLOPT_ENCODING), $this->equalTo('gzip'))
            );

        $curl->expects($this->once())
            ->method('exec')
            ->will($this->returnValue($body));

        $curl->expects($this->once())
            ->method('errno')
            ->will($this->returnValue(0));

        $curl->expects($this->once())
            ->method('getinfo')
            ->will($this->returnValue(array(
                'header_size' => 45,
                'http_code' => 200
            )));

        $request = new Request('GET', 'http://google.com', new \Bronto\DataObject(), $curl);
        $response = $request
            ->query("q", "this")
            ->query("q2", "that")
            ->respond();

        $this->assertEquals(200, $response->code());
        $this->assertEquals("Blade", $response->body());
        $this->assertEquals("Make-Believe", $response->header('Server'));
        $this->assertEquals("text/plain", $response->header('Content-Type'));
    }

    /**
     * @test
     * @group pmc
     */
    public function testPostParams()
    {
        $body = <<<RESPONSE
Server: Make-Believe
Content-Type: text/plain

Blade
RESPONSE;

        $curl = $this->_mockCurl();
        $curl->expects($this->once())
            ->method('init')
            ->with('http://google.com');

        $curl->expects($this->exactly(4))
            ->method('setopt')
            ->withConsecutive(
                array($this->equalTo(CURLOPT_RETURNTRANSFER), $this->equalTo(true)),
                array($this->equalTo(CURLOPT_HEADER), $this->equalTo(true)),
                array($this->equalTo(CURLOPT_ENCODING), $this->equalTo('gzip')),
                array($this->equalTo(CURLOPT_POSTFIELDS), $this->equalTo(array('big' => 'tasty', 'disc' => 'man')))
            );

        $curl->expects($this->once())
            ->method('exec')
            ->will($this->returnValue($body));

        $curl->expects($this->once())
            ->method('errno')
            ->will($this->returnValue(0));

        $curl->expects($this->once())
            ->method('getinfo')
            ->will($this->returnValue(array(
                'header_size' => 45,
                'http_code' => 200
            )));

        $request = new Request('POST', 'http://google.com', new \Bronto\DataObject(), $curl);
        $request->param('big', 'tasty')->param('disc', 'man');
        $response = $request->respond();
    }

    /**
     * @test
     * @group pmc
     */
    public function testHeaders()
    {
        $body = <<<RESPONSE
Server: Make-Believe
Content-Type: text/plain

Blade
RESPONSE;

        $curl = $this->_mockCurl();
        $curl->expects($this->once())
            ->method('init')
            ->with('http://google.com');

        $curl->expects($this->exactly(4))
            ->method('setopt')
            ->withConsecutive(
                array($this->equalTo(CURLOPT_RETURNTRANSFER), $this->equalTo(true)),
                array($this->equalTo(CURLOPT_HEADER), $this->equalTo(true)),
                array($this->equalTo(CURLOPT_ENCODING), $this->equalTo('gzip')),
                array($this->equalTo(CURLOPT_HTTPHEADER), $this->equalTo(array('Content-Type: application/json', 'Connection: keep-alive')))
            );

        $curl->expects($this->once())
            ->method('exec')
            ->will($this->returnValue($body));

        $curl->expects($this->once())
            ->method('errno')
            ->will($this->returnValue(0));

        $curl->expects($this->once())
            ->method('getinfo')
            ->will($this->returnValue(array(
                'header_size' => 45,
                'http_code' => 200
            )));

        $request = new Request('GET', 'http://google.com', new \Bronto\DataObject(), $curl);
        $request->header('Content-Type', 'application/json')
            ->header('Connection', 'keep-alive');
        $response = $request->respond();
    }

    /**
     * @test
     * @gorup pmc
     */
    public function testPurge()
    {
        $curl = $this->_mockCurl();
        $curl->expects($this->once())
            ->method('init')
            ->with('http://google.com');
        $curl->expects($this->exactly(5))
            ->method('setopt')
            ->withConsecutive(
                array($this->equalTo(CURLOPT_RETURNTRANSFER), $this->equalTo(true)),
                array($this->equalTo(CURLOPT_HEADER), $this->equalTo(true)),
                array($this->equalTo(CURLOPT_ENCODING), $this->equalTo('gzip')),
                array($this->equalTo(CURLOPT_CUSTOMREQUEST), $this->equalTo('PURGE')),
                array($this->equalTo(CURLOPT_HTTPHEADER), $this->equalTo(array("X-HTTP-Method-Override: PURGE")))
            );
        $request = new Request('PURGE', 'http://google.com', new \Bronto\DataObject(), $curl);
        $request->prepare();
    }

    /**
     * @test
     * @group pmc
     */
    public function testRawBody()
    {
        $body = <<<RESPONSE
Server: Make-Believe
Content-Type: text/plain

Blade
RESPONSE;

        $json = array('name' => 'Philip Cali', 'age' => 99);
        $curl = $this->_mockCurl();
        $curl->expects($this->once())
            ->method('init')
            ->with('http://google.com');

        $curl->expects($this->exactly(6))
            ->method('setopt')
            ->withConsecutive(
                array($this->equalTo(CURLOPT_RETURNTRANSFER), $this->equalTo(true)),
                array($this->equalTo(CURLOPT_HEADER), $this->equalTo(true)),
                array($this->equalTo(CURLOPT_ENCODING), $this->equalTo('gzip')),
                array($this->equalTo(CURLOPT_CUSTOMREQUEST), $this->equalTo('PUT')),
                array($this->equalTo(CURLOPT_HTTPHEADER), $this->equalTo(array('Content-Type: application/json', 'Connection: keep-alive', "X-HTTP-Method-Override: PUT"))),
                array($this->equalTo(CURLOPT_POSTFIELDS), $this->equalTo(json_encode($json)))
            );

        $curl->expects($this->once())
            ->method('exec')
            ->will($this->returnValue($body));

        $curl->expects($this->once())
            ->method('errno')
            ->will($this->returnValue(0));

        $curl->expects($this->once())
            ->method('getinfo')
            ->will($this->returnValue(array(
                'header_size' => 45,
                'http_code' => 200
            )));

        $request = new Request('PUT', 'http://google.com', new \Bronto\DataObject(), $curl);
        $request
            ->header('Content-Type', 'application/json')
            ->header('Connection', 'keep-alive')
            ->body(json_encode($json));
        $response = $request->respond();
    }

    /**
     * @test
     * @group pmc
     */
    public function testTransferException()
    {
        $curl = $this->_mockCurl();
        $curl->expects($this->once())
            ->method('init')
            ->with('http://google.com');

        $curl->expects($this->exactly(3))
            ->method('setopt')
            ->withConsecutive(
                array($this->equalTo(CURLOPT_RETURNTRANSFER), $this->equalTo(true)),
                array($this->equalTo(CURLOPT_HEADER), $this->equalTo(true)),
                array($this->equalTo(CURLOPT_ENCODING), $this->equalTo('gzip'))
            );

        $curl->expects($this->once())
            ->method('exec')
            ->will($this->returnValue(null));

        $curl->expects($this->once())
            ->method('errno')
            ->will($this->returnValue(404));

        $curl->expects($this->once())
            ->method('error')
            ->will($this->returnValue('Not Found!'));

        $request = new Request('GET', 'http://google.com', new \Bronto\DataObject(), $curl);
        try {
            $response = $request->respond();
            $this->fail('Should not have made it here.');
        } catch (\Bronto\Transfer\Exception $e) {
            $this->assertEquals(404, $e->getCode());
            $this->assertEquals('Not Found!', $e->getMessage());
            $this->assertEquals($request, $e->getRequest());
        }
    }

    /**
     * @test
     * @group pmc
     */
    public function testOn()
    {
        $mock = $this->getMockBuilder('\Bronto\DataObject')
            ->setMethods(array('getCalled'))
            ->getMock();
        $request = new Request('GET', 'http://google.com', new \Bronto\DataObject());
        $request->on('event', function($mock) {
            $mock->getCalled();
        });
        $mock->expects($this->once())
            ->method('getCalled')
            ->will($this->returnSelf());
        $request->event($mock);
    }
}
