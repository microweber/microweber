<?php
$config = array();
$config['name'] = "Stats module";
$config['author'] = "Microweber";
$config['ui'] = '0';
$config['on_install'] = "mw_install_stats_module";
$config['on_uninstall'] = "mw_uninstall_stats_module";
$config['position'] = 30;
$config['version'] = 0.3;



$config['tables'] = array();
$fields_to_add = array();

$fields_to_add[] = array('created_by', 'int(11) default NULL');
$fields_to_add[] = array('view_count', 'int(11) default 1');
$fields_to_add[] = array('user_ip', 'varchar(33)  default NULL ');
$fields_to_add[] = array('visit_date', 'date default NULL');
$fields_to_add[] = array('visit_time', 'time default NULL');
$fields_to_add[] = array('last_page', 'varchar(255)  default NULL ');
 
$config['tables']['table_stats_users_online'] = $fields_to_add;


 