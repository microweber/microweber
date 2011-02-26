<?php

require_once 'Sabre/DAV/AbstractServer.php';

class Sabre_DAV_ServerEventsTest extends Sabre_DAV_AbstractServer {

    private $tempPath;

    function testAfterBind() {

        $this->server->subscribeEvent('afterBind',array($this,'afterBindHandler'));
        $newPath = 'afterBind';

        $this->tempPath = '';
        $this->server->createFile($newPath,'body');
        $this->assertEquals($newPath, $this->tempPath); 

    }

    function afterBindHandler($path) {

       $this->tempPath = $path; 

    }


}

?>
