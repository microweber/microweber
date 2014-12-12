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

$fields_to_add[] = array('created_by', 'integer');
$fields_to_add[] = array('view_count', 'integer');
$fields_to_add[] = array('user_ip', 'string');
$fields_to_add[] = array('visit_date', 'date');
$fields_to_add[] = array('visit_time', 'time');
$fields_to_add[] = array('last_page', 'string');
$fields_to_add[] = array('session_id', 'string');

$config['tables']['stats_users_online'] = $fields_to_add;