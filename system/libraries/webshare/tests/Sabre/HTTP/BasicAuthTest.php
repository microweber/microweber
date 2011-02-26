<?php

require_once 'Sabre/HTTP/ResponseMock.php';

class Sabre_HTTP_BasicAuthTest extends PHPUnit_Framework_TestCase {

    private $response;
    private $request;
    private $basicAuth;

    function setUp() {

        $this->response = new Sabre_HTTP_ResponseMock();
        $this->basicAuth = new Sabre_HTTP_BasicAuth();
        $this->basicAuth->setHTTPResponse($this->response);

    }

    function testGetUserPassApache() {

        $server = array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => '1234',
        );

        $request = new Sabre_HTTP_Request($server);
        $this->basicAuth->setHTTPRequest($request);

        $userPass = $this->basicAuth->getUserPass();

        $this->assertEquals(
            array('admin','1234'),
            $userPass,
            'We did not get the username and password we expected'
        );

    }

    function testGetUserPassIIS() {

        $server = array(
            'HTTP_AUTHORIZATION' => 'Basic ' . base64_encode('admin:1234'),
        );

        $request = new Sabre_HTTP_Request($server);
        $this->basicAuth->setHTTPRequest($request);

        $userPass = $this->basicAuth->getUserPass();

        $this->assertEquals(
            array('admin','1234'),
            $userPass,
            'We did not get the username and password we expected'
        );

    }

    function testGetUserPassNothing() {

        $this->assertEquals(
            false,
            $this->basicAuth->getUserPass()
        );

    }

    function testRequireLogin() {

        $this->basicAuth->requireLogin();
        $this->assertEquals('SabreDAV',$this->basicAuth->getRealm());
        $this->assertEquals(
            'HTTP/1.1 401 Unauthorized',
            $this->response->status,
            'We expected a 401 status to be set'
        );

        $this->assertEquals(
            'Basic realm="SabreDAV"',
            $this->response->headers['WWW-Authenticate'],
            'The WWW-Autenticate header was not set!'
        );



    }

}

?>
