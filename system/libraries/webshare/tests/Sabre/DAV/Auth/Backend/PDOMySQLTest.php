<?php

require_once 'Sabre/TestUtil.php';

class Sabre_DAV_Auth_Backend_PDOMySQLTest extends Sabre_DAV_Auth_Backend_AbstractPDOTest {

    function getPDO() {

        if (!SABRE_HASMYSQL) $this->markTestSkipped('MySQL driver is not available, or not properly configured');
        $pdo = Sabre_TestUtil::getMySQLDB();
        if (!$pdo) $this->markTestSkipped('Could not connect to MySQL database');
        $pdo->query("DROP TABLE IF EXISTS users");
        $pdo->query("
create table users (
	id integer unsigned not null primary key auto_increment,
	username varchar(50),
	digesta1 varchar(32),
    email varchar(80),
	unique(username)
);");

        $pdo->query("INSERT INTO users (username,digesta1,email) VALUES ('user','hash','user@example.org')");

        return $pdo;

    }

}
