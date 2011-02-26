<?php

require_once 'Sabre/DAV/AbstractServer.php';

class Sabre_DAV_Browser_PluginTest extends Sabre_DAV_AbstractServer{

    function setUp() {

        parent::setUp();
        $this->server->addPlugin(new Sabre_DAV_Browser_Plugin());

    }

    function testCollectionGet() {

        $serverVars = array(
            'REQUEST_URI'    => '/',
            'REQUEST_METHOD' => 'GET',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $this->server->httpRequest = ($request);
        $this->server->exec();

        $this->assertEquals('HTTP/1.1 200 Ok',$this->response->status);
        $this->assertEquals(array(
            'Content-Type' => 'text/html; charset=utf-8',
            ),
            $this->response->headers
         );

    }


}
