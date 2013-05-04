<?php
include (MW_APPPATH . 'functions' . DIRECTORY_SEPARATOR . 'libs' . DIRECTORY_SEPARATOR . 'fSQL.php');

$dbf = DBPATH_FULL . "" . $db['dbname'];
$fsql = new fSQLEnvironment;
@touch($dbf);
$fsql -> define_db("mydb", DBPATH_FULL . "" . $db['dbname']);
$fsql -> select_db("mydb");

$results = $fsql -> query($q) or die("SELECT failed: " . $fsql -> error());
 
$fsql -> free_result($results);
