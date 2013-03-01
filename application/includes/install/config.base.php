<?php

defined('T') or die();

// Global site configuration
$config = array(
    // In development, debug mode unlocks extra error info
    'debug_mode' => false,
    'admin_url' => 'admin',
    'uri_protocol' => 'AUTO',
    'default_timezone' => '{default_timezone}',
	'table_prefix' => '{table_prefix}', 
    'installed' => '{IS_INSTALLED}',
    // Database Settings
    'db' => array(
		'type' => '{DB_TYPE}',
        'host' => '{DB_HOST}',
        'dbname' => '{dbname}',
        'user' => '{DB_USER}',
        'pass' => '{DB_PASS}'
    )
);
 
return $config;