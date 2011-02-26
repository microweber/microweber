<?php

require_once 'Sabre/DAV/AbstractServer.php';

class Sabre_DAV_Browser_MapGetToPropFindTest extends Sabre_DAV_AbstractServer {

    function setUp() {

        parent::setUp();
        $this->server->addPlugin(new Sabre_DAV_Browser_MapGetToPropFind());

    }

    function testCollectionGet() {

        $serverVars = array(
            'REQUEST_URI'    => '/',
            'REQUEST_METHOD' => 'GET',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $request->setBody('');
        $this->server->httpRequest = ($request);
        $this->server->exec();

        $this->assertEquals(array(
            'Content-Type' => 'application/xml; charset=utf-8',
            ),
            $this->response->headers
         );

        $this->assertEquals('HTTP/1.1 207 Multi-Status',$this->response->status,'Incorrect status response received. Full response body: ' . $this->response->body);

    }


}
