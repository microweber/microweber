<?php

defined('T') or die();

error_reporting(E_ALL);


// Global site configuration
$config = array(
    // In development, debug mode unlocks extra error info
    'debug_mode' => TRUE,
    'admin_url' => 'admin',
    'uri_protocol' => 'AUTO',
    'default_timezone' => 'UTC',
    'installed' => 'yes',
    // Database Settings
    'db' => array(
        //'dsn' => 'mysql:host=localhost;port=3306;dbname=mw_install',
        // 'dsn' => 'sqlite:db/default.db',
        'host' => 'localhost',
        'dbname' => 'mw_install',
        'user' => 'root',
        'pass' => '123456'
    )
);

return $config;