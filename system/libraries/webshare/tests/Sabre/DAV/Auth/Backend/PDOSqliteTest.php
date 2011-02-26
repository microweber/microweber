<?php

require_once 'Sabre/DAV/Auth/Backend/AbstractPDOTest.php';

class Sabre_DAV_Auth_Backend_PDOSQLiteTest extends Sabre_DAV_Auth_Backend_AbstractPDOTest {

    function tearDown() {

        if (file_exists(SABRE_TEMPDIR . '/pdobackend')) unlink(SABRE_TEMPDIR . '/pdobackend');
        if (file_exists(SABRE_TEMPDIR . '/pdobackend2')) unlink(SABRE_TEMPDIR . '/pdobackend2');

    }

    function getPDO() {

        if (!SABRE_HASSQLITE) $this->markTestSkipped('SQLite driver is not available');
        $pdo = new PDO('sqlite:'.SABRE_TEMPDIR.'/pdobackend');
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $pdo->query('CREATE TABLE users (username TEXT, digesta1 TEXT, email VARCHAR(80))');
        $pdo->query('INSERT INTO users VALUES ("user","hash","user@example.org")');

        return $pdo;

    }

}
