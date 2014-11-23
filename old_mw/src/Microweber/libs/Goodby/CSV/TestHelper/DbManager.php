<?php

namespace Goodby\CSV\TestHelper;

class DbManager
{
    private $pdo;

    private $host;
    private $db;
    private $user;
    private $pass;

    public function __construct()
    {
        $this->host = $_SERVER['GOODBY_CSV_TEST_DB_HOST'];
        $this->db   = $_SERVER['GOODBY_CSV_TEST_DB_NAME_DEFAULT'];
        $this->user = $_SERVER['GOODBY_CSV_TEST_DB_USER'];
        $this->pass = $_SERVER['GOODBY_CSV_TEST_DB_PASS'];

        $dsn = 'mysql:host=' . $this->host;

        $this->pdo = new \PDO($dsn, $this->user, $this->pass);
        $stmt = $this->pdo->prepare("CREATE DATABASE " . $this->db);

        $stmt->execute();
    }

    public function __destruct()
    {
        $stmt = $this->pdo->prepare("DROP DATABASE " . $this->db);
        $stmt->execute();
    }

    public function getPdo()
    {
        return new \PDO($this->getDsn(), $this->user, $this->pass);
    }

    public function getDsn()
    {
        return 'mysql:dbname=' . $this->db . ';host=' . $this->host;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getPassword()
    {
        return $this->pass;
    }
}
