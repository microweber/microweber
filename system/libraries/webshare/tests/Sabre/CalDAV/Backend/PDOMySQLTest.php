<?php

require_once 'Sabre/TestUtil.php';
require_once 'Sabre/CalDAV/TestUtil.php';
require_once 'Sabre/CalDAV/Backend/AbstractPDOTest.php';

class Sabre_CalDAV_Backend_PDOMySQLTest extends Sabre_CalDAV_Backend_AbstractPDOTest {
    
    function setup() {

        if (!SABRE_HASMYSQL) $this->markTestSkipped('MySQL driver is not available, or not properly configured');
        $pdo = Sabre_TestUtil::getMySQLDB();
        if (!$pdo) $this->markTestSkipped('Could not connect to mysql database');

        $pdo->query('DROP TABLE IF EXISTS calendarobjects, calendars');

        $pdo->query('CREATE TABLE calendarobjects ( 
            id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
            calendardata TEXT, 
            uri VARCHAR(100), 
            calendarid INTEGER UNSIGNED NOT NULL, 
            lastmodified DATETIME 
        );');

        $pdo->query('CREATE TABLE calendars (
            id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
            principaluri VARCHAR(100), 
            displayname VARCHAR(100), 
            uri VARCHAR(100), 
            ctag INTEGER UNSIGNED NOT NULL DEFAULT \'0\', 
            description TEXT, 
            calendarorder INTEGER UNSIGNED NOT NULL DEFAULT \'0\', 
            calendarcolor VARCHAR(10), 
            timezone TEXT, 
            components VARCHAR(20)
        );');

        $this->pdo = $pdo;

    }

    function teardown() {

        $this->pdo = null;

    }

}
