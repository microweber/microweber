<?php

require_once 'Sabre/HTTP/ResponseMock.php';

class Sabre_DAV_ServerPreconditionsTest extends PHPUnit_Framework_TestCase {

    /**
     * @covers Sabre_DAV_Server::checkPreconditions
     * @expectedException Sabre_DAV_Exception_PreconditionFailed
     */
    function testIfMatchNoNode() {

        $root = new Sabre_DAV_SimpleDirectory('root',array(new Sabre_DAV_ServerPreconditionsNode())); 
        $server = new Sabre_DAV_Server($root);
        $httpRequest = new Sabre_HTTP_Request(array(
            'HTTP_IF_MATCH' => '*',
            'REQUEST_URI'   => '/bar'
        ));
        $server->httpRequest = $httpRequest;

        $server->checkPreconditions();

    } 

    /**
     * @covers Sabre_DAV_Server::checkPreconditions
     */
    function testIfMatchHasNode() {

        $root = new Sabre_DAV_SimpleDirectory('root',array(new Sabre_DAV_ServerPreconditionsNode())); 
        $server = new Sabre_DAV_Server($root);
        $httpRequest = new Sabre_HTTP_Request(array(
            'HTTP_IF_MATCH' => '*',
            'REQUEST_URI'   => '/foo'
        ));
        $server->httpRequest = $httpRequest;

        $this->assertTrue($server->checkPreconditions());

    }

    /**
     * @covers Sabre_DAV_Server::checkPreconditions
     * @expectedException Sabre_DAV_Exception_PreconditionFailed
     */
    function testIfMatchWrongEtag() {

        $root = new Sabre_DAV_SimpleDirectory('root',array(new Sabre_DAV_ServerPreconditionsNode())); 
        $server = new Sabre_DAV_Server($root);
        $httpRequest = new Sabre_HTTP_Request(array(
            'HTTP_IF_MATCH' => '1234',
            'REQUEST_URI'   => '/foo'
        ));
        $server->httpRequest = $httpRequest;

        $server->checkPreconditions();

    } 

    /**
     * @covers Sabre_DAV_Server::checkPreconditions
     */
    function testIfMatchCorrectEtag() {

        $root = new Sabre_DAV_SimpleDirectory('root',array(new Sabre_DAV_ServerPreconditionsNode())); 
        $server = new Sabre_DAV_Server($root);
        $httpRequest = new Sabre_HTTP_Request(array(
            'HTTP_IF_MATCH' => '"abc123"',
            'REQUEST_URI'   => '/foo'
        ));
        $server->httpRequest = $httpRequest;

        $this->assertTrue($server->checkPreconditions());

    } 

    /**
     * @covers Sabre_DAV_Server::checkPreconditions
     */
    function testIfMatchMultiple() {

        $root = new Sabre_DAV_SimpleDirectory('root',array(new Sabre_DAV_ServerPreconditionsNode())); 
        $server = new Sabre_DAV_Server($root);
        $httpRequest = new Sabre_HTTP_Request(array(
            'HTTP_IF_MATCH' => '"hellothere", "abc123"',
            'REQUEST_URI'   => '/foo'
        ));
        $server->httpRequest = $httpRequest;

        $this->assertTrue($server->checkPreconditions());

    } 

    /**
     * @covers Sabre_DAV_Server::checkPreconditions
     */
    function testIfNoneMatchNoNode() {

        $root = new Sabre_DAV_SimpleDirectory('root',array(new Sabre_DAV_ServerPreconditionsNode())); 
        $server = new Sabre_DAV_Server($root);
        $httpRequest = new Sabre_HTTP_Request(array(
            'HTTP_IF_NONE_MATCH' => '*',
            'REQUEST_URI'   => '/bar'
        ));
        $server->httpRequest = $httpRequest;

        $this->assertTrue($server->checkPreconditions());

    } 

    /**
     * @covers Sabre_DAV_Server::checkPreconditions
     * @expectedException Sabre_DAV_Exception_PreconditionFailed
     */
    function testIfNoneMatchHasNode() {

        $root = new Sabre_DAV_SimpleDirectory('root',array(new Sabre_DAV_ServerPreconditionsNode())); 
        $server = new Sabre_DAV_Server($root);
        $httpRequest = new Sabre_HTTP_Request(array(
            'HTTP_IF_NONE_MATCH' => '*',
            'REQUEST_URI'   => '/foo'
        ));
        $server->httpRequest = $httpRequest;

        $server->checkPreconditions();

    }

    /**
     * @covers Sabre_DAV_Server::checkPreconditions
     */
    function testIfNoneMatchWrongEtag() {

        $root = new Sabre_DAV_SimpleDirectory('root',array(new Sabre_DAV_ServerPreconditionsNode())); 
        $server = new Sabre_DAV_Server($root);
        $httpRequest = new Sabre_HTTP_Request(array(
            'HTTP_IF_NONE_MATCH' => '"1234"',
            'REQUEST_URI'   => '/foo'
        ));
        $server->httpRequest = $httpRequest;

        $this->assertTrue($server->checkPreconditions());

    }

    /**
     * @covers Sabre_DAV_Server::checkPreconditions
     */
    function testIfNoneMatchWrongEtagMultiple() {

        $root = new Sabre_DAV_SimpleDirectory('root',array(new Sabre_DAV_ServerPreconditionsNode())); 
        $server = new Sabre_DAV_Server($root);
        $httpRequest = new Sabre_HTTP_Request(array(
            'HTTP_IF_NONE_MATCH' => '"1234", "5678"',
            'REQUEST_URI'   => '/foo'
        ));
        $server->httpRequest = $httpRequest;

        $this->assertTrue($server->checkPreconditions());

    } 

    /**
     * @covers Sabre_DAV_Server::checkPreconditions
     * @expectedException Sabre_DAV_Exception_PreconditionFailed
     */
    public function testIfNoneMatchCorrectEtag() {

        $root = new Sabre_DAV_SimpleDirectory('root',array(new Sabre_DAV_ServerPreconditionsNode())); 
        $server = new Sabre_DAV_Server($root);
        $httpRequest = new Sabre_HTTP_Request(array(
            'HTTP_IF_NONE_MATCH' => '"abc123"',
            'REQUEST_URI'   => '/foo'
        ));
        $server->httpRequest = $httpRequest;

        $server->checkPreconditions();

    }

    /**
     * @covers Sabre_DAV_Server::checkPreconditions
     * @expectedException Sabre_DAV_Exception_PreconditionFailed
     */
    public function testIfNoneMatchCorrectEtagMultiple() {

        $root = new Sabre_DAV_SimpleDirectory('root',array(new Sabre_DAV_ServerPreconditionsNode())); 
        $server = new Sabre_DAV_Server($root);
        $httpRequest = new Sabre_HTTP_Request(array(
            'HTTP_IF_NONE_MATCH' => '"1234", "abc123"',
            'REQUEST_URI'   => '/foo'
        ));
        $server->httpRequest = $httpRequest;

        $server->checkPreconditions();

    }

    /**
     * @covers Sabre_DAV_Server::checkPreconditions
     */
    public function testIfNoneMatchCorrectEtagAsGet() {

        $root = new Sabre_DAV_SimpleDirectory('root',array(new Sabre_DAV_ServerPreconditionsNode())); 
        $server = new Sabre_DAV_Server($root);
        $httpRequest = new Sabre_HTTP_Request(array(
            'HTTP_IF_NONE_MATCH' => '"abc123"',
            'REQUEST_URI'   => '/foo'
        ));
        $server->httpRequest = $httpRequest;
        $server->httpResponse = new Sabre_HTTP_ResponseMock();

        $this->assertFalse($server->checkPreconditions(true));
        $this->assertEquals('HTTP/1.1 304 Not Modified',$server->httpResponse->status);

    }

    /**
     * @covers Sabre_DAV_Server::checkPreconditions
     */
    public function testIfModifiedSinceUnModified() {

        $root = new Sabre_DAV_SimpleDirectory('root',array(new Sabre_DAV_ServerPreconditionsNode())); 
        $server = new Sabre_DAV_Server($root);
        $httpRequest = new Sabre_HTTP_Request(array(
            'HTTP_IF_MODIFIED_SINCE' => 'Sun, 06 Nov 1994 08:49:37 GMT',
            'REQUEST_URI'   => '/foo'
        ));
        $server->httpRequest = $httpRequest;
        $server->httpResponse = new Sabre_HTTP_ResponseMock();
        $this->assertFalse($server->checkPreconditions());

        $this->assertEquals('HTTP/1.1 304 Not Modified',$server->httpResponse->status);

    }


    /**
     * @covers Sabre_DAV_Server::checkPreconditions
     */
    public function testIfModifiedSinceModified() {

        $root = new Sabre_DAV_SimpleDirectory('root',array(new Sabre_DAV_ServerPreconditionsNode())); 
        $server = new Sabre_DAV_Server($root);
        $httpRequest = new Sabre_HTTP_Request(array(
            'HTTP_IF_MODIFIED_SINCE' => 'Tue, 06 Nov 1984 08:49:37 GMT',
            'REQUEST_URI'   => '/foo'
        ));
        $server->httpRequest = $httpRequest;
        $server->httpResponse = new Sabre_HTTP_ResponseMock();
        $this->assertTrue($server->checkPreconditions());

    }

    /**
     * @covers Sabre_DAV_Server::checkPreconditions
     */
    public function testIfModifiedSinceInvalidDate() {

        $root = new Sabre_DAV_SimpleDirectory('root',array(new Sabre_DAV_ServerPreconditionsNode())); 
        $server = new Sabre_DAV_Server($root);
        $httpRequest = new Sabre_HTTP_Request(array(
            'HTTP_IF_MODIFIED_SINCE' => 'Your mother',
            'REQUEST_URI'   => '/foo'
        ));
        $server->httpRequest = $httpRequest;
        $server->httpResponse = new Sabre_HTTP_ResponseMock();

        // Invalid dates must be ignored, so this should return true
        $this->assertTrue($server->checkPreconditions());

    }

    /**
     * @covers Sabre_DAV_Server::checkPreconditions
     */
    public function testIfModifiedSinceInvalidDate2() {

        $root = new Sabre_DAV_SimpleDirectory('root',array(new Sabre_DAV_ServerPreconditionsNode())); 
        $server = new Sabre_DAV_Server($root);
        $httpRequest = new Sabre_HTTP_Request(array(
            'HTTP_IF_MODIFIED_SINCE' => 'Sun, 06 Nov 1994 08:49:37 EST',
            'REQUEST_URI'   => '/foo'
        ));
        $server->httpRequest = $httpRequest;
        $server->httpResponse = new Sabre_HTTP_ResponseMock();
        $this->assertTrue($server->checkPreconditions());

    }


    /**
     * @covers Sabre_DAV_Server::checkPreconditions
     */
    public function testIfUnmodifiedSinceUnModified() {

        $root = new Sabre_DAV_SimpleDirectory('root',array(new Sabre_DAV_ServerPreconditionsNode())); 
        $server = new Sabre_DAV_Server($root);
        $httpRequest = new Sabre_HTTP_Request(array(
            'HTTP_IF_UNMODIFIED_SINCE' => 'Sun, 06 Nov 1994 08:49:37 GMT',
            'REQUEST_URI'   => '/foo'
        ));
        $server->httpRequest = $httpRequest;
        $this->assertTrue($server->checkPreconditions());

    }


    /**
     * @covers Sabre_DAV_Server::checkPreconditions
     * @expectedException Sabre_DAV_Exception_PreconditionFailed
     */
    public function testIfUnmodifiedSinceModified() {

        $root = new Sabre_DAV_SimpleDirectory('root',array(new Sabre_DAV_ServerPreconditionsNode())); 
        $server = new Sabre_DAV_Server($root);
        $httpRequest = new Sabre_HTTP_Request(array(
            'HTTP_IF_UNMODIFIED_SINCE' => 'Tue, 06 Nov 1984 08:49:37 GMT',
            'REQUEST_URI'   => '/foo'
        ));
        $server->httpRequest = $httpRequest;
        $server->httpResponse = new Sabre_HTTP_ResponseMock();
        $server->checkPreconditions();

    }

    /**
     * @covers Sabre_DAV_Server::checkPreconditions
     */
    public function testIfUnmodifiedSinceInvalidDate() {

        $root = new Sabre_DAV_SimpleDirectory('root',array(new Sabre_DAV_ServerPreconditionsNode())); 
        $server = new Sabre_DAV_Server($root);
        $httpRequest = new Sabre_HTTP_Request(array(
            'HTTP_IF_UNMODIFIED_SINCE' => 'Sun, 06 Nov 1984 08:49:37 CET',
            'REQUEST_URI'   => '/foo'
        ));
        $server->httpRequest = $httpRequest;
        $server->httpResponse = new Sabre_HTTP_ResponseMock();
        $this->assertTrue($server->checkPreconditions());

    }


}

class Sabre_DAV_ServerPreconditionsNode extends Sabre_DAV_File {

    function getETag() {
    
        return '"abc123"';

    }

    function getLastModified() {

        /* my birthday & time, I believe */
        return strtotime('1985-04-07 01:30 +02:00');

    }

    function getName() {

        return 'foo';

    }

}
