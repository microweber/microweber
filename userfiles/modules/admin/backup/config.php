<?php
$config = array();
$config['name'] = "DB Backup";
$config['author'] = "Microweber";
$config['no_cache'] = true;
 $config['categories'] = "admin"; 
$config['version'] = 0.1;



	$db = c('db');
 
		
// Settings
$table = '*';
$DBhost =  $db['host'];
$DBuser =  $db['user'];
$DBpass = $db['pass'];
$DBName = $db['dbname'];
?>