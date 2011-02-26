<?php

class Sabre_DAV_Auth_Backend_ApacheTest extends PHPUnit_Framework_TestCase {

    function testConstruct() {

        $backend = new Sabre_DAV_Auth_Backend_Apache();

    }

    /**
     * @expectedException Sabre_DAV_Exception
     */
    function testNoHeader() {

        $server = new Sabre_DAV_Server();
        $backend = new Sabre_DAV_Auth_Backend_Apache();
        $backend->authenticate($server,'Realm');

    }

    function testRemoteUser() {

        $backend = new Sabre_DAV_Auth_Backend_Apache();

        $server = new Sabre_DAV_Server();
        $request = new Sabre_HTTP_Request(array(
            'REMOTE_USER' => 'username',
        ));
        $server->httpRequest = $request;

        $this->assertTrue($backend->authenticate($server, 'Realm'));
        
        $userInfo = array(
            'uri' => 'principals/username',
        );

        $this->assertEquals($userInfo, $backend->getCurrentUser());
        $this->assertEquals(array($userInfo), $backend->getUsers());

    }

}
