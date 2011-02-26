<?php

require_once 'Sabre/HTTP/ResponseMock.php';

class Sabre_HTTP_ResponseTest extends PHPUnit_Framework_TestCase {

    private $response;

    function setUp() {

        $this->response = new Sabre_HTTP_ResponseMock();

    }

    function testGetStatusMessage() {

        $msg = $this->response->getStatusMessage(200);
        $this->assertEquals('HTTP/1.1 200 Ok',$msg);

    }

    function testSetHeader() {

        $this->response->setHeader('Content-Type','text/html');
        $this->assertEquals('text/html', $this->response->headers['Content-Type']);

    
    }

    function testSendStatus() {

        $this->response->sendStatus(404);
        $this->assertEquals('HTTP/1.1 404 Not Found', $this->response->status);

    }

    function testSendBody() {

        ob_start();
        $response = new Sabre_HTTP_Response();
        $response->sendBody('hello');
        $this->assertEquals('hello',ob_get_clean());

    }

    function testSendBodyStream() {

        ob_start();
        $stream = fopen('php://memory','r+');
        fwrite($stream,'hello');
        rewind($stream);
        $response = new Sabre_HTTP_Response();
        $response->sendBody($stream);
        $this->assertEquals('hello',ob_get_clean());

    }

}

?>
