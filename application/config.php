<?php

defined('T') or die();

// Global site configuration
$config = array(
    // In development, debug mode unlocks extra error info
    'debug_mode' => false,
    'admin_url' => 'admin',
    'uri_protocol' => 'AUTO',
    'default_timezone' => 'UTC',
    'installed' => 'yes',
    // Database Settings
    'db' => array(
        'host' => 'localhost',
        'dbname' => 'mw_install',
        'user' => 'root',
        'pass' => '123456'
    )
);
 
return $config;