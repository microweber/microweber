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
$fields_to_add[] = array('user_ip', 'varchar(33)  default NULL ');
$fields_to_add[] = array('visit_date', 'date default NULL');
$fields_to_add[] = array('visit_time', 'time default NULL');
$fields_to_add[] = array('last_page', 'string');
$fields_to_add[] = array('session_id', 'string');

$config['tables']['table_stats_users_online'] = $fields_to_add;