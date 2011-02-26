<?php

class Sabre_DAV_Locks_PluginTest extends Sabre_DAV_AbstractServer {

    protected $locksPlugin;

    function setUp() {

        parent::setUp();
        mkdir(SABRE_TEMPDIR . '/locksdir');
        $locksBackend = new Sabre_DAV_Locks_Backend_FS(SABRE_TEMPDIR . '/locksdir');
        $locksPlugin = new Sabre_DAV_Locks_Plugin($locksBackend);
        $this->server->addPlugin($locksPlugin);
        $this->locksPlugin = $locksPlugin;

    }

    function testGetFeatures() {

        $this->assertEquals(array(2),$this->locksPlugin->getFeatures()); 

    }
    
    function testGetHTTPMethods() {

        $this->assertEquals(array('LOCK','UNLOCK'),$this->locksPlugin->getHTTPMethods('')); 

    }

    function testGetHTTPMethodsNoBackend() {

        $locksPlugin = new Sabre_DAV_Locks_Plugin();
        $this->server->addPlugin($locksPlugin);
        $this->assertEquals(array(),$locksPlugin->getHTTPMethods('')); 

    }

    function testLockNoBody() {

        $serverVars = array(
            'REQUEST_URI'    => '/test.txt',
            'REQUEST_METHOD' => 'LOCK',
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

        $this->assertEquals('HTTP/1.1 400 Bad request',$this->response->status);

    }

    function testLock() {

        $serverVars = array(
            'REQUEST_URI'    => '/test.txt',
            'REQUEST_METHOD' => 'LOCK',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $request->setBody('<?xml version="1.0"?>
<D:lockinfo xmlns:D="DAV:"> 
    <D:lockscope><D:exclusive/></D:lockscope> 
    <D:locktype><D:write/></D:locktype> 
    <D:owner> 
        <D:href>http://example.org/~ejw/contact.html</D:href> 
    </D:owner> 
</D:lockinfo>');

        $this->server->httpRequest = $request;
        $this->server->exec();

        $this->assertEquals('application/xml; charset=utf-8',$this->response->headers['Content-Type']);
        $this->assertTrue(preg_match('/^<opaquelocktoken:(.*)>$/',$this->response->headers['Lock-Token'])===1,'We did not get a valid Locktoken back (' . $this->response->headers['Lock-Token'] . ')');

        $this->assertEquals('HTTP/1.1 200 Ok',$this->response->status,'Got an incorrect status back. Response body: ' . $this->response->body);

        $body = preg_replace("/xmlns(:[A-Za-z0-9_])?=(\"|\')DAV:(\"|\')/","xmlns\\1=\"urn:DAV\"",$this->response->body);
        $xml = simplexml_load_string($body);
        $xml->registerXPathNamespace('d','urn:DAV');

        $elements = array(
            '/d:prop',
            '/d:prop/d:lockdiscovery',
            '/d:prop/d:lockdiscovery/d:activelock',
            '/d:prop/d:lockdiscovery/d:activelock/d:locktype',
            '/d:prop/d:lockdiscovery/d:activelock/d:locktype/d:write',
            '/d:prop/d:lockdiscovery/d:activelock/d:lockscope',
            '/d:prop/d:lockdiscovery/d:activelock/d:lockscope/d:exclusive',
            '/d:prop/d:lockdiscovery/d:activelock/d:depth',
            '/d:prop/d:lockdiscovery/d:activelock/d:owner',
            '/d:prop/d:lockdiscovery/d:activelock/d:timeout',
            '/d:prop/d:lockdiscovery/d:activelock/d:locktoken',
            '/d:prop/d:lockdiscovery/d:activelock/d:locktoken/d:href',
        );

        foreach($elements as $elem) {
            $data = $xml->xpath($elem);
            $this->assertEquals(1,count($data),'We expected 1 match for the xpath expression "' . $elem . '". ' . count($data) . ' were found');
        }

        $depth = $xml->xpath('/d:prop/d:lockdiscovery/d:activelock/d:depth');
        $this->assertEquals('infinity',(string)$depth[0]);

        $token = $xml->xpath('/d:prop/d:lockdiscovery/d:activelock/d:locktoken/d:href');
        $this->assertEquals($this->response->headers['Lock-Token'],'<' . (string)$token[0] . '>','Token in response body didn\'t match token in response header.');

    }

    /**
     * @depends testLock
     */
    function testDoubleLock() {

        $serverVars = array(
            'REQUEST_URI'    => '/test.txt',
            'REQUEST_METHOD' => 'LOCK',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $request->setBody('<?xml version="1.0"?>
<D:lockinfo xmlns:D="DAV:"> 
    <D:lockscope><D:exclusive/></D:lockscope> 
    <D:locktype><D:write/></D:locktype> 
    <D:owner> 
        <D:href>http://example.org/~ejw/contact.html</D:href> 
    </D:owner> 
</D:lockinfo>');

        $this->server->httpRequest = $request;
        $this->server->exec();

        $this->response = new Sabre_HTTP_ResponseMock();
        $this->server->httpResponse = $this->response;

        $this->server->exec();

        $this->assertEquals('application/xml; charset=utf-8',$this->response->headers['Content-Type']);

        $this->assertEquals('HTTP/1.1 423 Locked',$this->response->status);

    }

    /**
     * @depends testLock
     */
    function testLockRefresh() {

        $serverVars = array(
            'REQUEST_URI'    => '/test.txt',
            'REQUEST_METHOD' => 'LOCK',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $request->setBody('<?xml version="1.0"?>
<D:lockinfo xmlns:D="DAV:"> 
    <D:lockscope><D:exclusive/></D:lockscope> 
    <D:locktype><D:write/></D:locktype> 
    <D:owner> 
        <D:href>http://example.org/~ejw/contact.html</D:href> 
    </D:owner> 
</D:lockinfo>');

        $this->server->httpRequest = $request;
        $this->server->exec();

        $lockToken = $this->response->headers['Lock-Token'];

        $this->response = new Sabre_HTTP_ResponseMock();
        $this->server->httpResponse = $this->response;

        $serverVars = array(
            'REQUEST_URI' => '/test.txt',
            'REQUEST_METHOD' => 'LOCK',
            'HTTP_IF' => '(' . $lockToken . ')',
        );
        $request = new Sabre_HTTP_Request($serverVars);
        $request->setBody('');
        $this->server->httpRequest = $request;

        $this->server->exec();

        $this->assertEquals('application/xml; charset=utf-8',$this->response->headers['Content-Type']);

        $this->assertEquals('HTTP/1.1 200 Ok',$this->response->status,'We received an incorrect status code. Full response body: ' . $this->response->body);

    }

    /**
     * @depends testLock
     */
    function testLockNoFile() {

        $serverVars = array(
            'REQUEST_URI'    => '/notfound.txt',
            'REQUEST_METHOD' => 'LOCK',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $request->setBody('<?xml version="1.0"?>
<D:lockinfo xmlns:D="DAV:"> 
    <D:lockscope><D:exclusive/></D:lockscope> 
    <D:locktype><D:write/></D:locktype> 
    <D:owner> 
        <D:href>http://example.org/~ejw/contact.html</D:href> 
    </D:owner> 
</D:lockinfo>');

        $this->server->httpRequest = $request;
        $this->server->exec();

        $this->assertEquals('application/xml; charset=utf-8',$this->response->headers['Content-Type']);
        $this->assertTrue(preg_match('/^<opaquelocktoken:(.*)>$/',$this->response->headers['Lock-Token'])===1,'We did not get a valid Locktoken back (' . $this->response->headers['Lock-Token'] . ')');

        $this->assertEquals('HTTP/1.1 201 Created',$this->response->status);

    }

    /**
     * @depends testLock
     */
    function testUnlockNoToken() {

        $serverVars = array(
            'REQUEST_URI'    => '/test.txt',
            'REQUEST_METHOD' => 'UNLOCK',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $this->server->httpRequest = ($request);
        $this->server->exec();

        $this->assertEquals(array(
            'Content-Type' => 'application/xml; charset=utf-8',
            ),
            $this->response->headers
         );

        $this->assertEquals('HTTP/1.1 400 Bad request',$this->response->status);

    }

    /**
     * @depends testLock
     */
    function testUnlockBadToken() {

        $serverVars = array(
            'REQUEST_URI'     => '/test.txt',
            'REQUEST_METHOD'  => 'UNLOCK',
            'HTTP_LOCK_TOKEN' => '<opaquelocktoken:blablabla>',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $this->server->httpRequest = ($request);
        $this->server->exec();

        $this->assertEquals(array(
            'Content-Type' => 'application/xml; charset=utf-8',
            ),
            $this->response->headers
         );

        $this->assertEquals('HTTP/1.1 409 Conflict',$this->response->status,'Got an incorrect status code. Full response body: ' . $this->response->body);

    }

    /**
     * @depends testLock
     */
    function testLockPutNoToken() {

        $serverVars = array(
            'REQUEST_URI'    => '/test.txt',
            'REQUEST_METHOD' => 'LOCK',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $request->setBody('<?xml version="1.0"?>
<D:lockinfo xmlns:D="DAV:"> 
    <D:lockscope><D:exclusive/></D:lockscope> 
    <D:locktype><D:write/></D:locktype> 
    <D:owner> 
        <D:href>http://example.org/~ejw/contact.html</D:href> 
    </D:owner> 
</D:lockinfo>');

        $this->server->httpRequest = $request;
        $this->server->exec();

        $this->assertEquals('application/xml; charset=utf-8',$this->response->headers['Content-Type']);
        $this->assertTrue(preg_match('/^<opaquelocktoken:(.*)>$/',$this->response->headers['Lock-Token'])===1,'We did not get a valid Locktoken back (' . $this->response->headers['Lock-Token'] . ')');

        $this->assertEquals('HTTP/1.1 200 Ok',$this->response->status);

        $serverVars = array(
            'REQUEST_URI'    => '/test.txt',
            'REQUEST_METHOD' => 'PUT',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $request->setBody('newbody');
        $this->server->httpRequest = $request;
        $this->server->exec();

        $this->assertEquals('application/xml; charset=utf-8',$this->response->headers['Content-Type']);
        $this->assertTrue(preg_match('/^<opaquelocktoken:(.*)>$/',$this->response->headers['Lock-Token'])===1,'We did not get a valid Locktoken back (' . $this->response->headers['Lock-Token'] . ')');

        $this->assertEquals('HTTP/1.1 423 Locked',$this->response->status);

    }

    /**
     * @depends testLock
     */
    function testUnlock() {

        $request = new Sabre_HTTP_Request(array());
        $this->server->httpRequest = $request;

        $request->setBody('<?xml version="1.0"?>
<D:lockinfo xmlns:D="DAV:"> 
    <D:lockscope><D:exclusive/></D:lockscope> 
    <D:locktype><D:write/></D:locktype> 
    <D:owner> 
        <D:href>http://example.org/~ejw/contact.html</D:href> 
    </D:owner> 
</D:lockinfo>');

        $this->server->invokeMethod('LOCK','test.txt');
        $lockToken = $this->server->httpResponse->headers['Lock-Token'];

        $serverVars = array(
            'HTTP_LOCK_TOKEN' => $lockToken, 
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $this->server->httpRequest = ($request);
        $this->server->httpResponse = new Sabre_HTTP_ResponseMock();
        $this->server->invokeMethod('UNLOCK', 'test.txt');

        $this->assertEquals('HTTP/1.1 204 No Content',$this->server->httpResponse->status,'Got an incorrect status code. Full response body: ' . $this->response->body);
        $this->assertEquals(array(
            'Content-Length' => '0',
            ),
            $this->server->httpResponse->headers
         );


    }

    /**
     * @depends testLock
     */
    function testLockPutBadToken() {

        $serverVars = array(
            'REQUEST_URI'    => '/test.txt',
            'REQUEST_METHOD' => 'LOCK',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $request->setBody('<?xml version="1.0"?>
<D:lockinfo xmlns:D="DAV:"> 
    <D:lockscope><D:exclusive/></D:lockscope> 
    <D:locktype><D:write/></D:locktype> 
    <D:owner> 
        <D:href>http://example.org/~ejw/contact.html</D:href> 
    </D:owner> 
</D:lockinfo>');

        $this->server->httpRequest = $request;
        $this->server->exec();

        $this->assertEquals('application/xml; charset=utf-8',$this->response->headers['Content-Type']);
        $this->assertTrue(preg_match('/^<opaquelocktoken:(.*)>$/',$this->response->headers['Lock-Token'])===1,'We did not get a valid Locktoken back (' . $this->response->headers['Lock-Token'] . ')');

        $this->assertEquals('HTTP/1.1 200 Ok',$this->response->status);

        $serverVars = array(
            'REQUEST_URI'    => '/test.txt',
            'REQUEST_METHOD' => 'PUT',
            'HTTP_IF' => '(<opaquelocktoken:token1>)',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $request->setBody('newbody');
        $this->server->httpRequest = $request;
        $this->server->exec();

        $this->assertEquals('application/xml; charset=utf-8',$this->response->headers['Content-Type']);
        $this->assertTrue(preg_match('/^<opaquelocktoken:(.*)>$/',$this->response->headers['Lock-Token'])===1,'We did not get a valid Locktoken back (' . $this->response->headers['Lock-Token'] . ')');

        $this->assertEquals('HTTP/1.1 412 Precondition failed',$this->response->status);

    }

    /**
     * @depends testLock
     */
    function testLockPutGoodToken() {

        $serverVars = array(
            'REQUEST_URI'    => '/test.txt',
            'REQUEST_METHOD' => 'LOCK',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $request->setBody('<?xml version="1.0"?>
<D:lockinfo xmlns:D="DAV:"> 
    <D:lockscope><D:exclusive/></D:lockscope> 
    <D:locktype><D:write/></D:locktype> 
    <D:owner> 
        <D:href>http://example.org/~ejw/contact.html</D:href> 
    </D:owner> 
</D:lockinfo>');

        $this->server->httpRequest = $request;
        $this->server->exec();

        $this->assertEquals('application/xml; charset=utf-8',$this->response->headers['Content-Type']);
        $this->assertTrue(preg_match('/^<opaquelocktoken:(.*)>$/',$this->response->headers['Lock-Token'])===1,'We did not get a valid Locktoken back (' . $this->response->headers['Lock-Token'] . ')');

        $this->assertEquals('HTTP/1.1 200 Ok',$this->response->status);

        $serverVars = array(
            'REQUEST_URI'    => '/test.txt',
            'REQUEST_METHOD' => 'PUT',
            'HTTP_IF' => '('.$this->response->headers['Lock-Token'].')',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $request->setBody('newbody');
        $this->server->httpRequest = $request;
        $this->server->exec();

        $this->assertEquals('application/xml; charset=utf-8',$this->response->headers['Content-Type']);
        $this->assertTrue(preg_match('/^<opaquelocktoken:(.*)>$/',$this->response->headers['Lock-Token'])===1,'We did not get a valid Locktoken back (' . $this->response->headers['Lock-Token'] . ')');

        $this->assertEquals('HTTP/1.1 200 Ok',$this->response->status);

    }

    function testPutWithIncorrectETag() {

        $serverVars = array(
            'REQUEST_URI'    => '/test.txt',
            'REQUEST_METHOD' => 'PUT',
            'HTTP_IF' => '(["etag1"])',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $request->setBody('newbody');
        $this->server->httpRequest = $request;
        $this->server->exec();
        $this->assertEquals('HTTP/1.1 412 Precondition failed',$this->response->status);

    }

    /**
     * @depends testPutWithIncorrectETag
     */
    function testPutWithCorrectETag() {

        // We need an etag-enabled file node.
        $tree = new Sabre_DAV_ObjectTree(new Sabre_DAV_FSExt_Directory(SABRE_TEMPDIR));
        $this->server->tree = $tree;

        $etag = md5(file_get_contents(SABRE_TEMPDIR . '/test.txt'));
        $serverVars = array(
            'REQUEST_URI'    => '/test.txt',
            'REQUEST_METHOD' => 'PUT',
            'HTTP_IF' => '(["'.$etag.'"])',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $request->setBody('newbody');
        $this->server->httpRequest = $request;
        $this->server->exec();
        $this->assertEquals('HTTP/1.1 200 Ok',$this->response->status);

    }

    function testGetTimeoutHeader() {

        $request = new Sabre_HTTP_Request(array(
            'HTTP_TIMEOUT' => 'second-100',
        ));

        $this->server->httpRequest = $request;
        $this->assertEquals(100, $this->locksPlugin->getTimeoutHeader());

    }


    function testGetTimeoutHeaderNotSet() {

        $request = new Sabre_HTTP_Request(array(
        ));

        $this->server->httpRequest = $request;
        $this->assertEquals(0, $this->locksPlugin->getTimeoutHeader());

    }


    function testGetTimeoutHeaderInfinite() {

        $request = new Sabre_HTTP_Request(array(
            'HTTP_TIMEOUT' => 'infinite',
        ));

        $this->server->httpRequest = $request;
        $this->assertEquals(Sabre_DAV_Locks_LockInfo::TIMEOUT_INFINITE, $this->locksPlugin->getTimeoutHeader());

    }

    /**
     * @expectedException Sabre_DAV_Exception_BadRequest
     */
    function testGetTimeoutHeaderInvalid() {

        $request = new Sabre_HTTP_Request(array(
            'HTTP_TIMEOUT' => 'yourmom',
        ));

        $this->server->httpRequest = $request;
        $this->locksPlugin->getTimeoutHeader();

    }
    

}
