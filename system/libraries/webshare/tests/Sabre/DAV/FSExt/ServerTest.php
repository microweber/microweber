<?php

require_once 'Sabre/DAV/AbstractServer.php';

class Sabre_DAV_FSExt_ServerTest extends Sabre_DAV_AbstractServer{

    protected function getRootNode() {

        return new Sabre_DAV_FSExt_Directory($this->tempDir);

    }

    function testGet() {
        
        $serverVars = array(
            'REQUEST_URI'    => '/test.txt',
            'REQUEST_METHOD' => 'GET',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $this->server->httpRequest = ($request);
        $this->server->exec();

        $this->assertEquals(array(
            'Content-Type' => 'application/octet-stream',
            'Content-Length' => 13,
            'Last-Modified' => date(DateTime::RFC1123,filemtime($this->tempDir . '/test.txt')),
            'ETag' => '"'  .md5_file($this->tempDir . '/test.txt') . '"',
            ),
            $this->response->headers
         );

        $this->assertEquals('HTTP/1.1 200 Ok',$this->response->status);
        $this->assertEquals('Test contents', stream_get_contents($this->response->body));

    }

    function testHEAD() {
        
        $serverVars = array(
            'REQUEST_URI'    => '/test.txt',
            'REQUEST_METHOD' => 'HEAD',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $this->server->httpRequest = ($request);
        $this->server->exec();

        $this->assertEquals(array(
            'Content-Type' => 'application/octet-stream',
            'Content-Length' => 13,
            'Last-Modified' => date(DateTime::RFC1123,filemtime($this->tempDir . '/test.txt')),
            'ETag' => '"' . md5_file($this->tempDir . '/test.txt') . '"',
            ),
            $this->response->headers
         );

        $this->assertEquals('HTTP/1.1 200 Ok',$this->response->status);
        $this->assertEquals('', $this->response->body);

    }

    function testPut() {

        $serverVars = array(
            'REQUEST_URI'    => '/testput.txt',
            'REQUEST_METHOD' => 'PUT',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $request->setBody('Testing new file');
        $this->server->httpRequest = ($request);
        $this->server->exec();

        $this->assertEquals(array(
            'Content-Length' => '0',
        ),$this->response->headers);

        $this->assertEquals('HTTP/1.1 201 Created',$this->response->status);
        $this->assertEquals('', $this->response->body);
        $this->assertEquals('Testing new file',file_get_contents($this->tempDir . '/testput.txt'));

    }

    function testPutAlreadyExists() {

        $serverVars = array(
            'REQUEST_URI'    => '/test.txt',
            'REQUEST_METHOD' => 'PUT',
            'HTTP_IF_NONE_MATCH' => '*',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $request->setBody('Testing new file');
        $this->server->httpRequest = ($request);
        $this->server->exec();

        $this->assertEquals(array(
            'Content-Type' => 'application/xml; charset=utf-8',
        ),$this->response->headers);

        $this->assertEquals('HTTP/1.1 412 Precondition failed',$this->response->status);
        $this->assertNotEquals('Testing new file',file_get_contents($this->tempDir . '/test.txt'));

    }

    function testMkcol() {

        $serverVars = array(
            'REQUEST_URI'    => '/testcol',
            'REQUEST_METHOD' => 'MKCOL',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $request->setBody("");
        $this->server->httpRequest = ($request);
        $this->server->exec();

        $this->assertEquals(array(
            'Content-Length' => '0',
        ),$this->response->headers);

        $this->assertEquals('HTTP/1.1 201 Created',$this->response->status);
        $this->assertEquals('', $this->response->body);
        $this->assertTrue(is_dir($this->tempDir . '/testcol'));

    }

    function testPutUpdate() {

        $serverVars = array(
            'REQUEST_URI'    => '/test.txt',
            'REQUEST_METHOD' => 'PUT',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $request->setBody('Testing updated file');
        $this->server->httpRequest = ($request);
        $this->server->exec();

        $this->assertEquals(array(
            'Content-Length' => '0',
        ),$this->response->headers);

        $this->assertEquals('HTTP/1.1 200 Ok',$this->response->status);
        $this->assertEquals('', $this->response->body);
        $this->assertEquals('Testing updated file',file_get_contents($this->tempDir . '/test.txt'));

    }

    function testDelete() {

        $serverVars = array(
            'REQUEST_URI'    => '/test.txt',
            'REQUEST_METHOD' => 'DELETE',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $this->server->httpRequest = ($request);
        $this->server->exec();

        $this->assertEquals(array(
            'Content-Length' => '0',
        ),$this->response->headers);

        $this->assertEquals('HTTP/1.1 204 No Content',$this->response->status);
        $this->assertEquals('', $this->response->body);
        $this->assertFalse(file_exists($this->tempDir . '/test.txt'));

    }

    function testDeleteDirectory() {

        $serverVars = array(
            'REQUEST_URI'    => '/testcol',
            'REQUEST_METHOD' => 'DELETE',
        );

        mkdir($this->tempDir.'/testcol');
        file_put_contents($this->tempDir.'/testcol/test.txt','Hi! I\'m a file with a short lifespan');

        $request = new Sabre_HTTP_Request($serverVars);
        $this->server->httpRequest = ($request);
        $this->server->exec();

        $this->assertEquals(array(
            'Content-Length' => '0',
        ),$this->response->headers);
        $this->assertEquals('HTTP/1.1 204 No Content',$this->response->status);
        $this->assertEquals('', $this->response->body);
        $this->assertFalse(file_exists($this->tempDir . '/col'));

    }

    function testOptions() {

        $serverVars = array(
            'REQUEST_URI'    => '/',
            'REQUEST_METHOD' => 'OPTIONS',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $this->server->httpRequest = ($request);
        $this->server->exec();

        $this->assertEquals(array(
            'DAV'            => '1, 3, extended-mkcol',
            'MS-Author-Via'  => 'DAV',
            'Allow'          => 'OPTIONS, GET, HEAD, DELETE, PROPFIND, PUT, PROPPATCH, COPY, MOVE, REPORT',
            'Accept-Ranges'  => 'bytes',
            'Content-Length' => '0',
            'X-Sabre-Version'=> Sabre_DAV_Version::VERSION,
        ),$this->response->headers);

        $this->assertEquals('HTTP/1.1 200 Ok',$this->response->status);
        $this->assertEquals('', $this->response->body);

    }

}

?>
