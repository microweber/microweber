<?php

require_once 'Sabre/HTTP/ResponseMock.php';

class Sabre_DAV_Auth_PrincipalPropertySearchTest extends PHPUnit_Framework_TestCase {

    function getServer() {

        $backend = new Sabre_DAV_Auth_MockBackend();

        $dir = new Sabre_DAV_SimpleDirectory('root');
        $principals = new Sabre_DAV_Auth_PrincipalCollection($backend);
        $dir->addChild($principals);

        $fakeServer = new Sabre_DAV_Server(new Sabre_DAV_ObjectTree($dir));
        $fakeServer->httpResponse = new Sabre_HTTP_ResponseMock();
        $plugin = new Sabre_DAV_Auth_Plugin($backend,'realm');
        $this->assertTrue($plugin instanceof Sabre_DAV_Auth_Plugin);
        $fakeServer->addPlugin($plugin);
        $this->assertEquals($plugin, $fakeServer->getPlugin('Sabre_DAV_Auth_Plugin'));

        return $fakeServer;

    }

    function testUnsupportedUri() {

        $xml = '<?xml version="1.0"?>
<d:principal-property-search xmlns:d="DAV:">
  <d:property-search>
     <d:prop>
       <d:displayname />
     </d:prop>
     <d:match>user1</d:match>
  </d:property-search>
  <d:prop>
    <d:displayname />
    <d:getcontentlength />
  </d:prop>
</d:principal-property-search>';

        $serverVars = array(
            'REQUEST_METHOD' => 'REPORT',
            'HTTP_DEPTH'     => '0',
            'REQUEST_URI'    => '/',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $request->setBody($xml);

        $server = $this->getServer();
        $server->httpRequest = $request;

        $server->exec();

        $this->assertEquals('HTTP/1.1 501 Not Implemented', $server->httpResponse->status);
        $this->assertEquals(array(
            'Content-Type' => 'application/xml; charset=utf-8',
        ), $server->httpResponse->headers);

    }

    
    function testDepth1() {

        $xml = '<?xml version="1.0"?>
<d:principal-property-search xmlns:d="DAV:">
  <d:property-search>
     <d:prop>
       <d:displayname />
     </d:prop>
     <d:match>user1</d:match>
  </d:property-search>
  <d:prop>
    <d:displayname />
    <d:getcontentlength />
  </d:prop>
</d:principal-property-search>';

        $serverVars = array(
            'REQUEST_METHOD' => 'REPORT',
            'HTTP_DEPTH'     => '1',
            'REQUEST_URI'    => '/principals',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $request->setBody($xml);

        $server = $this->getServer();
        $server->httpRequest = $request;

        $server->exec();

        $this->assertEquals('HTTP/1.1 400 Bad request', $server->httpResponse->status);
        $this->assertEquals(array(
            'Content-Type' => 'application/xml; charset=utf-8',
        ), $server->httpResponse->headers);

    }

    
    function testUnknownSearchField() {

        $xml = '<?xml version="1.0"?>
<d:principal-property-search xmlns:d="DAV:">
  <d:property-search>
     <d:prop>
       <d:yourmom />
     </d:prop>
     <d:match>user1</d:match>
  </d:property-search>
  <d:prop>
    <d:displayname />
    <d:getcontentlength />
  </d:prop>
</d:principal-property-search>';

        $serverVars = array(
            'REQUEST_METHOD' => 'REPORT',
            'HTTP_DEPTH'     => '0',
            'REQUEST_URI'    => '/principals',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $request->setBody($xml);

        $server = $this->getServer();
        $server->httpRequest = $request;

        $server->exec();

        $this->assertEquals('HTTP/1.1 400 Bad request', $server->httpResponse->status);
        $this->assertEquals(array(
            'Content-Type' => 'application/xml; charset=utf-8',
        ), $server->httpResponse->headers);

    }

    function testCorrect() {

        $xml = '<?xml version="1.0"?>
<d:principal-property-search xmlns:d="DAV:">
  <d:property-search>
     <d:prop>
       <d:displayname />
     </d:prop>
     <d:match>user1</d:match>
  </d:property-search>
  <d:prop>
    <d:displayname />
    <d:getcontentlength />
  </d:prop>
</d:principal-property-search>';

        $serverVars = array(
            'REQUEST_METHOD' => 'REPORT',
            'HTTP_DEPTH'     => '0',
            'REQUEST_URI'    => '/principals',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $request->setBody($xml);

        $server = $this->getServer();
        $server->httpRequest = $request;

        $server->exec();

        $this->assertEquals('HTTP/1.1 207 Multi-Status', $server->httpResponse->status, $server->httpResponse->body);
        $this->assertEquals(array(
            'Content-Type' => 'application/xml; charset=utf-8',
        ), $server->httpResponse->headers);

        
        $check = array(
            '/d:multistatus',
            '/d:multistatus/d:response' => 1,
            '/d:multistatus/d:response/d:href' => 1,
            '/d:multistatus/d:response/d:propstat' => 2,
            '/d:multistatus/d:response/d:propstat/d:prop' => 2,
            '/d:multistatus/d:response/d:propstat/d:prop/d:displayname' => 1,
            '/d:multistatus/d:response/d:propstat/d:prop/d:getcontentlength' => 1,
            '/d:multistatus/d:response/d:propstat/d:status' => 2,
        );

        $xml = simplexml_load_string($server->httpResponse->body);
        $xml->registerXPathNamespace('d','DAV:');
        foreach($check as $v1=>$v2) {

            $xpath = is_int($v1)?$v2:$v1;

            $result = $xml->xpath($xpath);

            $count = 1;
            if (!is_int($v1)) $count = $v2;

            $this->assertEquals($count,count($result), 'we expected ' . $count . ' appearances of ' . $xpath . ' . We found ' . count($result) . '. Full response body: ' . $server->httpResponse->body);

        }

    }
}
