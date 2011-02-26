<?php

require_once 'Sabre/TestUtil.php';
require_once 'Sabre/DAV/Locks/Backend/AbstractTest.php';

class Sabre_DAV_Locks_Backend_PDOTest extends Sabre_DAV_Locks_Backend_AbstractTest {

    function getBackend() {

        if (!SABRE_HASSQLITE) $this->markTestSkipped('SQLite driver is not available');
        Sabre_TestUtil::clearTempDir();
        mkdir(SABRE_TEMPDIR . '/pdolocks');
        $pdo = new PDO('sqlite:' . SABRE_TEMPDIR . '/pdolocks/db.sqlite');
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $pdo->query('CREATE TABLE locks ( id integer primary key asc, owner text, timeout text, created integer, token text, scope integer, depth integer, uri text)');
        $backend = new Sabre_DAV_Locks_Backend_PDO($pdo);
        return $backend;

    }

    function tearDown() {

        Sabre_TestUtil::clearTempDir();

    }

}
