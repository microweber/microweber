<?php

class Sabre_DAV_TestPlugin extends Sabre_DAV_ServerPlugin {

    public $beforeMethod;

    function getFeatures() {

        return array('drinking');

    }

    function getHTTPMethods($uri) {

        return array('BEER','WINE');

    }

    function initialize(Sabre_DAV_Server $server) {

        $server->subscribeEvent('beforeMethod',array($this,'beforeMethod'));

    }

    function beforeMethod($method) {

        $this->beforeMethod = $method;
        return true;

    }

}
