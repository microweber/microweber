<?php

require_once 'Sabre/DAV/Auth/MockBackend.php';
require_once 'Sabre/HTTP/ResponseMock.php';

class Sabre_DAV_Auth_PluginTest extends PHPUnit_Framework_TestCase {

    function testInit() {

        $fakeServer = new Sabre_DAV_Server(new Sabre_DAV_ObjectTree(new Sabre_DAV_SimpleDirectory('bla')));
        $plugin = new Sabre_DAV_Auth_Plugin(new Sabre_DAV_Auth_MockBackend(),'realm');
        $this->assertTrue($plugin instanceof Sabre_DAV_Auth_Plugin);
        $fakeServer->addPlugin($plugin);
        $this->assertEquals($plugin, $fakeServer->getPlugin('Sabre_DAV_Auth_Plugin'));

    }

    /**
     * @depends testInit
     */
    function testAuthenticate() {

        $fakeServer = new Sabre_DAV_Server(new Sabre_DAV_ObjectTree(new Sabre_DAV_SimpleDirectory('bla')));
        $plugin = new Sabre_DAV_Auth_Plugin(new Sabre_DAV_Auth_MockBackend(),'realm');
        $fakeServer->addPlugin($plugin);
        $fakeServer->broadCastEvent('beforeMethod',array('GET','/'));

    }

    function testCurrentUserPrincipal() {

        $fakeServer = new Sabre_DAV_Server(new Sabre_DAV_ObjectTree(new Sabre_DAV_SimpleDirectory('bla')));
        $plugin = new Sabre_DAV_Auth_Plugin(new Sabre_DAV_Auth_MockBackend(),'realm');
        $fakeServer->addPlugin($plugin);


        $props = $fakeServer->getProperties('',array('{DAV:}current-user-principal'));
        $this->assertArrayHasKey('{DAV:}current-user-principal', $props);

        $this->assertEquals(Sabre_DAV_Property_Principal::UNAUTHENTICATED, $props['{DAV:}current-user-principal']->getType());

        // This will force the login
        $fakeServer->broadCastEvent('beforeMethod',array('GET','/'));

        $props = $fakeServer->getProperties('',array('{DAV:}current-user-principal'));
        $this->assertArrayHasKey('{DAV:}current-user-principal', $props);

        $this->assertEquals(Sabre_DAV_Property_Principal::HREF, $props['{DAV:}current-user-principal']->getType());
        $this->assertEquals('principals/admin', $props['{DAV:}current-user-principal']->getHref());
    }

    function testPrincipalCollectionSet() {

        $fakeServer = new Sabre_DAV_Server(new Sabre_DAV_ObjectTree(new Sabre_DAV_SimpleDirectory('bla')));
        $plugin = new Sabre_DAV_Auth_Plugin(new Sabre_DAV_Auth_MockBackend(),'realm');
        $fakeServer->addPlugin($plugin);

        $props = $fakeServer->getProperties('',array('{DAV:}principal-collection-set'));
        $this->assertArrayHasKey('{DAV:}principal-collection-set', $props);

        $this->assertEquals('principals', $props['{DAV:}principal-collection-set']->getHref());

    }

    /**
     * @depends testInit
     * @expectedException Sabre_DAV_Exception_NotAuthenticated
     */
    function testAuthenticateFail() {

        $fakeServer = new Sabre_DAV_Server(new Sabre_DAV_ObjectTree(new Sabre_DAV_SimpleDirectory('bla')));
        $plugin = new Sabre_DAV_Auth_Plugin(new Sabre_DAV_Auth_MockBackend(),'failme');
        $fakeServer->addPlugin($plugin);
        $fakeServer->broadCastEvent('beforeMethod',array('GET','/'));

    }

    function testSupportedReportSet() {

        $fakeServer = new Sabre_DAV_Server(new Sabre_DAV_ObjectTree(new Sabre_DAV_SimpleDirectory('bla')));
        $plugin = new Sabre_DAV_Auth_Plugin(new Sabre_DAV_Auth_MockBackend(),'realm');
        $fakeServer->addPlugin($plugin);

        $props = $fakeServer->getProperties('',array('{DAV:}supported-report-set'));

        $this->assertArrayHasKey('{DAV:}supported-report-set',$props);
        $this->assertTrue($props['{DAV:}supported-report-set'] instanceof Sabre_DAV_Property_SupportedReportSet);

        $this->assertEquals(array(
              '{DAV:}expand-property'
            ), $props['{DAV:}supported-report-set']->getValue());

    }

    function testReportPassThrough() {

        $fakeServer = new Sabre_DAV_Server(new Sabre_DAV_ObjectTree(new Sabre_DAV_SimpleDirectory('bla')));
        $plugin = new Sabre_DAV_Auth_Plugin(new Sabre_DAV_Auth_MockBackend(),'realm');
        $fakeServer->addPlugin($plugin);

        $request = new Sabre_HTTP_Request(array(
            'REQUEST_METHOD' => 'REPORT',
            'HTTP_CONTENT_TYPE' => 'application/xml',
            'REQUEST_URI' => '/',
        ));
        $request->setBody('<?xml version="1.0"?><s:somereport xmlns:s="http://www.rooftopsolutions.nl/NS/example" />');

        $fakeServer->httpRequest = $request;
        $fakeServer->httpResponse = new Sabre_HTTP_ResponseMock();
        $fakeServer->exec();

        $this->assertEquals('HTTP/1.1 501 Not Implemented', $fakeServer->httpResponse->status);

    }

}

