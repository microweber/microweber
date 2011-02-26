<?php

require_once 'Sabre/CalDAV/Backend/AbstractPDOTest.php';

class Sabre_CalDAV_Backend_PDOSQLiteTest extends Sabre_CalDAV_Backend_AbstractPDOTest {
    
    function setup() {

        if (!SABRE_HASSQLITE) $this->markTestSkipped('SQLite driver is not available');
        $this->pdo = Sabre_CalDAV_TestUtil::getSQLiteDB();

    }

    function teardown() {

        $this->pdo = null;
        unlink(SABRE_TEMPDIR . '/testdb.sqlite');

    }

}
