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
	
	'installed' => '{is_installed}',
	'autoinstall' => '{autoinstall}',
	'with_default_content' => '{with_default_content}',
	'default_template' => '{default_template}',
	// database settings
	'db' => array(
		'type' => '{db_type}',
		'host' => '{db_host}',
		'dbname' => '{dbname}',
		'user' => '{db_user}',
		'pass' => '{db_pass}'
	),
	
	'admin_username' => '{admin_username}', 
	'admin_password' => '{admin_password}', 
	'admin_email' => '{admin_email}'
		
);
 
 