<?php

require_once 'Sabre/HTTP/ResponseMock.php';

class Sabre_DAV_Auth_ExpandPropertiesTest extends PHPUnit_Framework_TestCase {

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

    function testSimple() {

        $xml = '<?xml version="1.0"?>
<d:expand-property xmlns:d="DAV:">
  <d:property name="displayname" />
  <d:property name="foo" namespace="http://www.sabredav.org/NS/2010/nonexistant" />
  <d:property name="current-user-principal" />
</d:expand-property>';

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

        $this->assertEquals('HTTP/1.1 207 Multi-Status', $server->httpResponse->status);
        $this->assertEquals(array(
            'Content-Type' => 'application/xml; charset=utf-8',
        ), $server->httpResponse->headers);

        
        $check = array(
            '/d:multistatus',
            '/d:multistatus/d:response' => 3,
            '/d:multistatus/d:response/d:href' => 3,
            '/d:multistatus/d:response/d:propstat' => 6,
            '/d:multistatus/d:response/d:propstat/d:prop' => 6,
            '/d:multistatus/d:response/d:propstat/d:prop/d:displayname' => 3,
            '/d:multistatus/d:response/d:propstat/d:prop/d:current-user-principal' => 3,
            '/d:multistatus/d:response/d:propstat/d:prop/d:current-user-principal/d:href' => 3,
        );

        $xml = simplexml_load_string($server->httpResponse->body);
        $xml->registerXPathNamespace('d','DAV:');
        foreach($check as $v1=>$v2) {

            $xpath = is_int($v1)?$v2:$v1;

            $result = $xml->xpath($xpath);

            $count = 1;
            if (!is_int($v1)) $count = $v2;

            $this->assertEquals($count,count($result), 'we expected ' . $count . ' appearances of ' . $xpath . ' . We found ' . count($result));

        }

    }

    /**
     * @depends testSimple
     */
    function testExpand() {

        $xml = '<?xml version="1.0"?>
<d:expand-property xmlns:d="DAV:">
  <d:property name="current-user-principal">
      <d:property name="displayname" />
  </d:property>
</d:expand-property>';

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

        $this->assertEquals('HTTP/1.1 207 Multi-Status', $server->httpResponse->status);
        $this->assertEquals(array(
            'Content-Type' => 'application/xml; charset=utf-8',
        ), $server->httpResponse->headers);

  
        $check = array(
            '/d:multistatus',
            '/d:multistatus/d:response' => 1,
            '/d:multistatus/d:response/d:href' => 1,
            '/d:multistatus/d:response/d:propstat' => 1,
            '/d:multistatus/d:response/d:propstat/d:prop' => 1,
            '/d:multistatus/d:response/d:propstat/d:prop/d:current-user-principal' => 1,
            '/d:multistatus/d:response/d:propstat/d:prop/d:current-user-principal/d:response' => 1,
            '/d:multistatus/d:response/d:propstat/d:prop/d:current-user-principal/d:response/d:href' => 1,
            '/d:multistatus/d:response/d:propstat/d:prop/d:current-user-principal/d:response/d:propstat' => 1,
            '/d:multistatus/d:response/d:propstat/d:prop/d:current-user-principal/d:response/d:propstat/d:prop' => 1,
            '/d:multistatus/d:response/d:propstat/d:prop/d:current-user-principal/d:response/d:propstat/d:prop/d:displayname' => 1,
        );

        $xml = simplexml_load_string($server->httpResponse->body);
        $xml->registerXPathNamespace('d','DAV:');
        foreach($check as $v1=>$v2) {

            $xpath = is_int($v1)?$v2:$v1;

            $result = $xml->xpath($xpath);

            $count = 1;
            if (!is_int($v1)) $count = $v2;

            $this->assertEquals($count,count($result), 'we expected ' . $count . ' appearances of ' . $xpath . ' . We found ' . count($result));

        }

    }
}
