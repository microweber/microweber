<?php
$config = array();
$config['name'] = "Site stats";
$config['author'] = "Microweber";
$config['ui_admin'] = false;

$config['ui'] = false;
$config['position'] = 30;
$config['version'] = 0.3;
$config['type'] = "stats";

$config['tables'] = array();
$fields_to_add = array();
$fields_to_add['created_by'] = 'integer';
$fields_to_add['view_count'] = ['type' => 'integer', 'default' => 1];
$fields_to_add['user_ip'] = 'string';
$fields_to_add['visit_date'] = 'date';
$fields_to_add['visit_time'] = 'time';
$fields_to_add['last_page'] = 'string';
$fields_to_add['session_id'] = 'string';
 
$config['tables']['stats_users_online'] = $fields_to_add; 
