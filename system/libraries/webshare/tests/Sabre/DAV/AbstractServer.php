<?php

require_once 'Sabre/HTTP/ResponseMock.php';

abstract class Sabre_DAV_AbstractServer extends PHPUnit_Framework_TestCase {

    protected $response;
    protected $request;
    protected $server;
    protected $tempDir = SABRE_TEMPDIR; 

    function setUp() {

        $this->response = new Sabre_HTTP_ResponseMock();
        $this->server = new Sabre_DAV_Server($this->getRootNode());
        $this->server->httpResponse = $this->response;
        $this->server->debugExceptions = true;
        file_put_contents(SABRE_TEMPDIR . '/test.txt', 'Test contents');

    }

    function tearDown() {

        $this->deleteTree(SABRE_TEMPDIR,false);

    }

    protected function getRootNode() {

        return new Sabre_DAV_FS_Directory(SABRE_TEMPDIR);

    }

    private function deleteTree($path,$deleteRoot = true) {

        foreach(scandir($path) as $node) {

            if ($node=='.' || $node=='.svn' || $node=='..') continue;
            $myPath = $path.'/'. $node;
            if (is_file($myPath)) {
                unlink($myPath);
            } else {
                $this->deleteTree($myPath);
            }

        }
        if ($deleteRoot) rmdir($path);

    }

}

?>
