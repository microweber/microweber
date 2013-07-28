<?php
include (MW_APP_PATH . 'functions' . DIRECTORY_SEPARATOR . 'libs' . DIRECTORY_SEPARATOR . 'fSQL.php');

$dbf = MW_STORAGE_DIR . "" . $db['dbname'];
$fsql = new fSQLEnvironment;
@touch($dbf);
$fsql -> define_db("mydb", MW_STORAGE_DIR . "" . $db['dbname']);
$fsql -> select_db("mydb");

$results = $fsql -> query($q) or die("SELECT failed: " . $fsql -> error());
 
$fsql -> free_result($results);
