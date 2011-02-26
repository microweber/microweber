<?php

class Sabre_DAV_TemporaryFileFilterTest extends Sabre_DAV_AbstractServer {

    function setUp() {

        parent::setUp();
        $plugin = new Sabre_DAV_TemporaryFileFilterPlugin(SABRE_TEMPDIR . '/tff');
        $this->server->addPlugin($plugin);

    }

    function testPutNormal() {

        $serverVars = array(
            'REQUEST_URI'    => '/testput.txt',
            'REQUEST_METHOD' => 'PUT',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $request->setBody('Testing new file');
        $this->server->httpRequest = ($request);
        $this->server->exec();

        $this->assertEquals('', $this->response->body);
        $this->assertEquals('HTTP/1.1 201 Created',$this->response->status);
        $this->assertEquals(array(
            'Content-Length' => '0',
        ),$this->response->headers);

        $this->assertEquals('Testing new file',file_get_contents(SABRE_TEMPDIR . '/testput.txt'));

    }

    function testPutTemp() {

        // mimicking an OS/X resource fork
        $serverVars = array(
            'REQUEST_URI'    => '/._testput.txt',
            'REQUEST_METHOD' => 'PUT',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $request->setBody('Testing new file');
        $this->server->httpRequest = ($request);
        $this->server->exec();

        $this->assertEquals('', $this->response->body);
        $this->assertEquals('HTTP/1.1 201 Created',$this->response->status);
        $this->assertEquals(array(
            'X-Sabre-Temp' => 'true',
        ),$this->response->headers);

        $this->assertFalse(file_exists(SABRE_TEMPDIR . '/._testput.txt'),'._testput.txt should not exist in the regular file structure.');

    }

    function testPutTempIfNoneMatch() {

        // mimicking an OS/X resource fork
        $serverVars = array(
            'REQUEST_URI'        => '/._testput.txt',
            'REQUEST_METHOD'     => 'PUT',
            'HTTP_IF_NONE_MATCH' => '*',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $request->setBody('Testing new file');
        $this->server->httpRequest = ($request);
        $this->server->exec();

        $this->assertEquals('', $this->response->body);
        $this->assertEquals('HTTP/1.1 201 Created',$this->response->status);
        $this->assertEquals(array(
            'X-Sabre-Temp' => 'true',
        ),$this->response->headers);

        $this->assertFalse(file_exists(SABRE_TEMPDIR . '/._testput.txt'),'._testput.txt should not exist in the regular file structure.');


        $this->server->exec();

        $this->assertEquals('HTTP/1.1 412 Precondition failed',$this->response->status);
        $this->assertEquals(array(
            'X-Sabre-Temp' => 'true',
            'Content-Type' => 'application/xml; charset=utf-8',
        ),$this->response->headers);

    }

    function testPutGet() {

        // mimicking an OS/X resource fork
        $serverVars = array(
            'REQUEST_URI'    => '/._testput.txt',
            'REQUEST_METHOD' => 'PUT',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $request->setBody('Testing new file');
        $this->server->httpRequest = ($request);
        $this->server->exec();

        $this->assertEquals('', $this->response->body);
        $this->assertEquals('HTTP/1.1 201 Created',$this->response->status);
        $this->assertEquals(array(
            'X-Sabre-Temp' => 'true',
        ),$this->response->headers);

        $serverVars = array(
            'REQUEST_URI'    => '/._testput.txt',
            'REQUEST_METHOD' => 'GET',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $this->server->httpRequest = ($request);
        $this->server->exec();

        $this->assertEquals('HTTP/1.1 200 Ok',$this->response->status);
        $this->assertEquals(array(
            'X-Sabre-Temp' => 'true',
            'Content-Length' => 16,
            'Content-Type' => 'application/octet-stream',
        ),$this->response->headers);

        $this->assertEquals('Testing new file',stream_get_contents($this->response->body));

    }

    function testLockNonExistant() {

        mkdir(SABRE_TEMPDIR . '/locksdir');
        $locksBackend = new Sabre_DAV_Locks_Backend_FS(SABRE_TEMPDIR . '/locksdir');
        $locksPlugin = new Sabre_DAV_Locks_Plugin($locksBackend);
        $this->server->addPlugin($locksPlugin);

        // mimicking an OS/X resource fork
        $serverVars = array(
            'REQUEST_URI'    => '/._testlock.txt',
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

        $this->server->httpRequest = ($request);
        $this->server->exec();

        $this->assertEquals('HTTP/1.1 201 Created',$this->response->status);
        $this->assertEquals('application/xml; charset=utf-8',$this->response->headers['Content-Type']);
        $this->assertTrue(preg_match('/^<opaquelocktoken:(.*)>$/',$this->response->headers['Lock-Token'])===1,'We did not get a valid Locktoken back (' . $this->response->headers['Lock-Token'] . ')');
        $this->assertEquals('true',$this->response->headers['X-Sabre-Temp']);
        
        $this->assertFalse(file_exists(SABRE_TEMPDIR . '/._testlock.txt'),'._testlock.txt should not exist in the regular file structure.');

    }

    function testPutDelete() {

        // mimicking an OS/X resource fork
        $serverVars = array(
            'REQUEST_URI'    => '/._testput.txt',
            'REQUEST_METHOD' => 'PUT',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $request->setBody('Testing new file');
        $this->server->httpRequest = ($request);
        $this->server->exec();

        $this->assertEquals('', $this->response->body);
        $this->assertEquals('HTTP/1.1 201 Created',$this->response->status);
        $this->assertEquals(array(
            'X-Sabre-Temp' => 'true',
        ),$this->response->headers);

        $serverVars = array(
            'REQUEST_URI'    => '/._testput.txt',
            'REQUEST_METHOD' => 'DELETE',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $this->server->httpRequest = ($request);
        $this->server->exec();

        $this->assertEquals('HTTP/1.1 204 No Content',$this->response->status, "Incorrect status code received. Full body:\n". $this->response->body);
        $this->assertEquals(array(
            'X-Sabre-Temp' => 'true',
        ),$this->response->headers);

        $this->assertEquals('',$this->response->body);

    }

    function testPutPropfind() {

        // mimicking an OS/X resource fork
        $serverVars = array(
            'REQUEST_URI'    => '/._testput.txt',
            'REQUEST_METHOD' => 'PUT',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $request->setBody('Testing new file');
        $this->server->httpRequest = ($request);
        $this->server->exec();

        $this->assertEquals('', $this->response->body);
        $this->assertEquals('HTTP/1.1 201 Created',$this->response->status);
        $this->assertEquals(array(
            'X-Sabre-Temp' => 'true',
        ),$this->response->headers);

        $serverVars = array(
            'REQUEST_URI'    => '/._testput.txt',
            'REQUEST_METHOD' => 'PROPFIND',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $request->setBody('');
        $this->server->httpRequest = ($request);
        $this->server->exec();

        $this->assertEquals('HTTP/1.1 207 Multi-Status',$this->response->status,'Incorrect status code returned. Body: ' . $this->response->body);
        $this->assertEquals(array(
            'X-Sabre-Temp' => 'true',
            'Content-Type' => 'application/xml; charset=utf-8',
        ),$this->response->headers);

        $body = preg_replace("/xmlns(:[A-Za-z0-9_])?=(\"|\')DAV:(\"|\')/","xmlns\\1=\"urn:DAV\"",$this->response->body);
        $xml = simplexml_load_string($body);
        $xml->registerXPathNamespace('d','urn:DAV');

        list($data) = $xml->xpath('/d:multistatus/d:response/d:href');
        $this->assertEquals('/._testput.txt',(string)$data,'href element should have been /._testput.txt');

        $data = $xml->xpath('/d:multistatus/d:response/d:propstat/d:prop/d:resourcetype');
        $this->assertEquals(1,count($data));
        
    }

}
