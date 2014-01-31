<?php

$here = __DIR__;
require_once($here.'/../bootstrap.php');


$config = array(
    // In development, debug mode unlocks extra error info
    'debug_mode' => 1,
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

mw()->config = $config;