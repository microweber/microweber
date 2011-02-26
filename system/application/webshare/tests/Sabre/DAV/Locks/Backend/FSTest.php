<?php

require_once 'Sabre/TestUtil.php';

class Sabre_DAV_Locks_Backend_FSTest extends Sabre_DAV_Locks_Backend_AbstractTest {

    function getBackend() {

        Sabre_TestUtil::clearTempDir();
        mkdir(SABRE_TEMPDIR . '/locks');
        $backend = new Sabre_DAV_Locks_Backend_FS(SABRE_TEMPDIR . '/locks/');
        return $backend;

    }

    function tearDown() {

        Sabre_TestUtil::clearTempDir();

    }

}
