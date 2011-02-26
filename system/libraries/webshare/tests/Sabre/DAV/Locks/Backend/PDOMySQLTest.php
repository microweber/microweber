<?php

require_once 'Sabre/TestUtil.php';

class Sabre_DAV_Locks_Backend_PDOMySQLTest extends Sabre_DAV_Locks_Backend_AbstractTest {

    function getBackend() {

        if (!SABRE_HASMYSQL) $this->markTestSkipped('MySQL driver is not available, or it was not properly configured');
        $pdo = Sabre_TestUtil::getMySQLDB();
        if (!$pdo) $this->markTestSkipped('Could not connect to MySQL database');
        $pdo->query('DROP TABLE IF EXISTS locks;');
        $pdo->query("
CREATE TABLE locks ( 
	id INTEGER UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT, 
	owner VARCHAR(100), 
	timeout INTEGER UNSIGNED,
	created INTEGER, 
	token VARCHAR(100),
	scope TINYINT, 
	depth TINYINT, 
	uri text
);");

        $backend = new Sabre_DAV_Locks_Backend_PDO($pdo);
        return $backend;

    }

}
