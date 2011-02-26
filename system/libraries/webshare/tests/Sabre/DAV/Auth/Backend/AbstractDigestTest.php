<?php

require_once 'Sabre/HTTP/ResponseMock.php';

class Sabre_DAV_Auth_Backend_AbstractDigestTest extends PHPUnit_Framework_TestCase {

    public function testGetUsers() {

        $backend = new Sabre_DAV_Auth_Backend_AbstractDigestMock();
        $this->assertEquals(array(),$backend->getUsers());

    }

    /**
     * @expectedException Sabre_DAV_Exception_NotAuthenticated
     */
    public function testAuthenticateNoHeaders() {

        $response = new Sabre_HTTP_ResponseMock();
        $tree = new Sabre_DAV_ObjectTree(new Sabre_DAV_SimpleDirectory('bla'));
        $server = new Sabre_DAV_Server($tree);
        $server->httpResponse = $response;

        $backend = new Sabre_DAV_Auth_Backend_AbstractDigestMock();
        $backend->authenticate($server,'myRealm');

    }

    /**
     * @expectedException Sabre_DAV_Exception
     */
    public function testAuthenticateBadGetUserInfoResponse() {

        $response = new Sabre_HTTP_ResponseMock();
        $tree = new Sabre_DAV_ObjectTree(new Sabre_DAV_SimpleDirectory('bla'));
        $server = new Sabre_DAV_Server($tree);
        $server->httpResponse = $response;

        $header = 'username=null, realm=myRealm, nonce=12345, uri=/, response=HASH, opaque=1, qop=auth, nc=1, cnonce=1';
        $request = new Sabre_HTTP_Request(array(
            'PHP_AUTH_DIGEST' => $header,
        ));
        $server->httpRequest = $request;

        $backend = new Sabre_DAV_Auth_Backend_AbstractDigestMock();
        $backend->authenticate($server,'myRealm');

    }

    /**
     * @expectedException Sabre_DAV_Exception
     */
    public function testAuthenticateBadGetUserInfoResponse2() {

        $response = new Sabre_HTTP_ResponseMock();
        $tree = new Sabre_DAV_ObjectTree(new Sabre_DAV_SimpleDirectory('bla'));
        $server = new Sabre_DAV_Server($tree);
        $server->httpResponse = $response;

        $header = 'username=array, realm=myRealm, nonce=12345, uri=/, response=HASH, opaque=1, qop=auth, nc=1, cnonce=1';
        $request = new Sabre_HTTP_Request(array(
            'PHP_AUTH_DIGEST' => $header,
        ));
        $server->httpRequest = $request;

        $backend = new Sabre_DAV_Auth_Backend_AbstractDigestMock();
        $backend->authenticate($server,'myRealm');

    }

    /**
     * @expectedException Sabre_DAV_Exception_NotAuthenticated
     */
    public function testAuthenticateUnknownUser() {

        $response = new Sabre_HTTP_ResponseMock();
        $tree = new Sabre_DAV_ObjectTree(new Sabre_DAV_SimpleDirectory('bla'));
        $server = new Sabre_DAV_Server($tree);
        $server->httpResponse = $response;

        $header = 'username=false, realm=myRealm, nonce=12345, uri=/, response=HASH, opaque=1, qop=auth, nc=1, cnonce=1';
        $request = new Sabre_HTTP_Request(array(
            'PHP_AUTH_DIGEST' => $header,
        ));
        $server->httpRequest = $request;

        $backend = new Sabre_DAV_Auth_Backend_AbstractDigestMock();
        $backend->authenticate($server,'myRealm');

    }

    /**
     * @expectedException Sabre_DAV_Exception_NotAuthenticated
     */
    public function testAuthenticateBadPassword() {

        $response = new Sabre_HTTP_ResponseMock();
        $tree = new Sabre_DAV_ObjectTree(new Sabre_DAV_SimpleDirectory('bla'));
        $server = new Sabre_DAV_Server($tree);
        $server->httpResponse = $response;

        $header = 'username=user, realm=myRealm, nonce=12345, uri=/, response=HASH, opaque=1, qop=auth, nc=1, cnonce=1';
        $request = new Sabre_HTTP_Request(array(
            'PHP_AUTH_DIGEST' => $header,
            'REQUEST_METHOD'  => 'PUT',
        ));
        $server->httpRequest = $request;

        $backend = new Sabre_DAV_Auth_Backend_AbstractDigestMock();
        $backend->authenticate($server,'myRealm');

    }

    public function testAuthenticate() {

        $response = new Sabre_HTTP_ResponseMock();
        $tree = new Sabre_DAV_ObjectTree(new Sabre_DAV_SimpleDirectory('bla'));
        $server = new Sabre_DAV_Server($tree);
        $server->httpResponse = $response;

        $digestHash = md5('HELLO:12345:1:1:auth:' . md5('GET:/'));
        $header = 'username=user, realm=myRealm, nonce=12345, uri=/, response='.$digestHash.', opaque=1, qop=auth, nc=1, cnonce=1';
        $request = new Sabre_HTTP_Request(array(
            'REQUEST_METHOD'  => 'GET',
            'PHP_AUTH_DIGEST' => $header,
            'REQUEST_URI'     => '/',
        ));
        $server->httpRequest = $request;

        $backend = new Sabre_DAV_Auth_Backend_AbstractDigestMock();
        $this->assertTrue($backend->authenticate($server,'myRealm'));

        $result = $backend->getCurrentUser();

        $this->assertEquals($backend->getUserInfo('','user'), $result);

    }


}


class Sabre_DAV_Auth_Backend_AbstractDigestMock extends Sabre_DAV_Auth_Backend_AbstractDigest {

    function getUserInfo($realm, $userName) {

        switch($userName) {
            case 'null' : return null;
            case 'false' : return false;
            case 'array' : return array();
            case 'user'  : return array('uri' => 'principals/user', 'digestHash' => 'HELLO');
        }

    }

}
