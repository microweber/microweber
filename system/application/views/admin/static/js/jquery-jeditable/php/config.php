<?php

error_reporting(E_ALL ^ E_NOTICE);

try {
    $dbh = new PDO('sqlite:/tmp/editable.sqlite');
} catch(PDOException $e) {
    print $e->getMessage();
}

/* Create table for storing example data. */
$dbh->exec('
CREATE TABLE config (id INTEGER primary key, 
                     token VARCHAR(255),
                     value TEXT,
                     date DATETIME)
');
