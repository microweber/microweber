<?php

/**
 * @covers Sabre_HTTP_Request
 */
class Sabre_HTTP_RequestTest extends PHPUnit_Framework_TestCase {

    private $request;

    function setUp() {

        $server = array(
            'HTTP_HOST'      => 'www.example.org',
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI'    => '/testuri/',
        );

        $this->request = new Sabre_HTTP_Request($server);

    }

    function testGetHeader() {

        $this->assertEquals('www.example.org', $this->request->getHeader('Host'), 'We didn\'t get back a valid value while requesting for an HTTP header');

    }

    function testGetNonExistantHeader() {

        $this->assertEquals(null,$this->request->getHeader('doesntexist'), 'When we ask for a header that doesn\'t exist, a null-value is expected');

    }

    function testGetMethod() {

        $this->assertEquals('PUT', $this->request->getMethod(), 'It seems as if we didn\'t get a valid HTTP Request method back');

    }

    function testGetUri() {

        $this->assertEquals('/testuri/', $this->request->getUri(), 'We got an invalid uri back');

    }

    function testSetGetBody() {

        $h = fopen('php://memory','r+');
        fwrite($h,'testing');
        rewind($h);
        $this->request->setBody($h);
        $this->assertEquals('testing',$this->request->getBody(true),'We didn\'t get our testbody back');

    }

    function testDefaultInputStream() {

        $h = fopen('php://memory','r+');
        fwrite($h,'testing');
        rewind($h);

        $previousValue = Sabre_HTTP_Request::$defaultInputStream;
        Sabre_HTTP_Request::$defaultInputStream = $h;

        $this->assertEquals('testing',$this->request->getBody(true),'We didn\'t get our testbody back');
        Sabre_HTTP_Request::$defaultInputStream = $previousValue;

    }

    function testGetAbsoluteUri() {

        $s = array(
            'HTTP_HOST' => 'sabredav.org',
            'REQUEST_URI' => '/foo'
        );

        $r = new Sabre_HTTP_Request($s);

        $this->assertEquals('http://sabredav.org/foo', $r->getAbsoluteUri());

        $s = array(
            'HTTP_HOST'   => 'sabredav.org',
            'REQUEST_URI' => '/foo',
            'HTTPS'       => 'on',
        );

        $r = new Sabre_HTTP_Request($s);

        $this->assertEquals('https://sabredav.org/foo', $r->getAbsoluteUri());

    }

}

?>
