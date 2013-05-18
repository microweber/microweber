<?php

defined('T') or die();

// Global site configuration
$config = array(
    // In development, debug mode unlocks extra error info
    'debug_mode' => false,
    'admin_url' => 'admin',
    'uri_protocol' => 'AUTO',
    'default_timezone' => 'UTC',
	'table_prefix' => 'mw_',

    // Database Settings
    'db' => array(
		'type' => 'mysql',
        'host' => 'localhost',
        'dbname' => 'test',
        'user' => 'root',
        'pass' => ''
    )
);

return $config;