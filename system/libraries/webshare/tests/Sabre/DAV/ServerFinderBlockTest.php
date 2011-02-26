<?php

require_once 'Sabre/HTTP/ResponseMock.php';
require_once 'Sabre/DAV/AbstractServer.php';
require_once 'Sabre/DAV/Exception.php';

class Sabre_DAV_ServerFinderBlockTest extends Sabre_DAV_AbstractServer{

    function testPut() {

        $serverVars = array(
            'REQUEST_URI'    => '/testput.txt',
            'REQUEST_METHOD' => 'PUT',
            'HTTP_X_EXPECTED_ENTITY_LENGTH' => '20',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $request->setBody('Testing finder');
        $this->server->httpRequest = $request;
        $this->server->exec();

        $this->assertEquals('', $this->response->body);
        $this->assertEquals('HTTP/1.1 201 Created',$this->response->status);
        $this->assertEquals(array(
            'Content-Length' => '0',
        ),$this->response->headers);

        $this->assertEquals('Testing finder',file_get_contents(SABRE_TEMPDIR . '/testput.txt'));

    }

    function testPutFail() {

        $serverVars = array(
            'REQUEST_URI'    => '/testput.txt',
            'REQUEST_METHOD' => 'PUT',
            'HTTP_X_EXPECTED_ENTITY_LENGTH' => '20',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $request->setBody('');
        $this->server->httpRequest = $request;
        $this->server->exec();

        $this->assertEquals('HTTP/1.1 403 Forbidden',$this->response->status);
        $this->assertEquals(array(
            'Content-Type' => 'application/xml; charset=utf-8',
        ),$this->response->headers);

        $this->assertFalse(file_exists(SABRE_TEMPDIR . '/testput.txt'));
    }
}

?>
