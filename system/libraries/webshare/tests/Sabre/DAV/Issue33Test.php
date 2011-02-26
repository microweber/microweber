<?php

require_once 'Sabre/TestUtil.php';

class Sabre_DAV_Issue33Test extends PHPUnit_FrameWork_TestCase {

    function setUp() {

        Sabre_TestUtil::clearTempDir();

    }

    function testCopyMoveInfo() {

        $foo = new Sabre_DAV_SimpleDirectory('foo');
        $root = new Sabre_DAV_SimpleDirectory('webdav',array($foo));

        $tree = new Sabre_DAV_ObjectTree($root);
        $server = new Sabre_DAV_Server($tree);
        $server->setBaseUri('/webdav/');

        $serverVars = array(
            'REQUEST_URI' => '/webdav/foo',
            'HTTP_DESTINATION' => 'http://dev2.tribalos.com/webdav/%C3%A0fo%C3%B3',
            'HTTP_OVERWRITE' => 'F',
        );
    
        $request = new Sabre_HTTP_Request($serverVars);

        $server->httpRequest = $request;

        $info = $server->getCopyAndMoveInfo();

        $this->assertEquals('%C3%A0fo%C3%B3', urlencode($info['destination']));
        $this->assertFalse($info['destinationExists']);
        $this->assertFalse($info['destinationNode']);

    }

    function testTreeMove() {

        mkdir(SABRE_TEMPDIR . '/issue33');
        $dir = new Sabre_DAV_FS_Directory(SABRE_TEMPDIR . '/issue33');

        $dir->createDirectory('foo');

        $tree = new Sabre_DAV_ObjectTree($dir);
        $tree->move('foo',urldecode('%C3%A0fo%C3%B3'));

        $node = $tree->getNodeForPath(urldecode('%C3%A0fo%C3%B3'));
        $this->assertEquals(urldecode('%C3%A0fo%C3%B3'),$node->getName()); 

    }

    function testDirName() {

        $dirname1 = 'foo';
        $dirname2 = urlencode('%C3%A0fo%C3%B3');;

        $this->assertTrue(dirname($dirname1)==dirname($dirname2));

    }

    /**
     * @depends testTreeMove
     * @depends testCopyMoveInfo
     */
    function testEverything() {
   
        // Request object
        $serverVars = array(
            'REQUEST_METHOD' => 'MOVE',
            'REQUEST_URI' => '/webdav/foo',
            'HTTP_DESTINATION' => 'http://dev2.tribalos.com/webdav/%C3%A0fo%C3%B3',
            'HTTP_OVERWRITE' => 'F',
        );

        $request = new Sabre_HTTP_Request($serverVars);
        $request->setBody('');

        $response = new Sabre_HTTP_ResponseMock();

        // Server setup
        mkdir(SABRE_TEMPDIR . '/issue33');
        $dir = new Sabre_DAV_FS_Directory(SABRE_TEMPDIR . '/issue33');

        $dir->createDirectory('foo');

        $tree = new Sabre_DAV_ObjectTree($dir);

        $server = new Sabre_DAV_Server($tree);
        $server->setBaseUri('/webdav/');

        $server->httpRequest = $request;
        $server->httpResponse = $response;
        $server->exec();

        $this->assertTrue(file_exists(SABRE_TEMPDIR  . '/issue33/' . urldecode('%C3%A0fo%C3%B3')));

    }

}
