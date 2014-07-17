<?php

if (!defined("MW_UNIT_TEST")) {
    define('MW_UNIT_TEST',1);
}


error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once(__DIR__.'/../../../vendor/autoload.php');

require_once(__DIR__.'/../bootstrap.php');
 
$config = array(
    // In development, debug mode unlocks extra error info
    'debug_mode' => true,
    'admin_url' => 'admin',
    'uri_protocol' => 'AUTO',
    'default_timezone' => 'UTC',
    'table_prefix' => $GLOBALS['db_table_prefix'],
    'installed' => 'yes',
    // Database Settings
    'db' => array(
        'type' => $GLOBALS['db_type'],
        'host' => $GLOBALS['db_host'].':'.$GLOBALS['db_port'],
        'dbname' => $GLOBALS['db_name'],
        'user' => $GLOBALS['db_username'],
        'pass' => $GLOBALS['db_password']
    )
);


mw()->set_config($config);