<?php
$config = array();
$config['name'] = "Backup";
$config['author'] = "Microweber";
 
$config['categories'] = "admin"; 
$config['version'] = 0.3;
$config['ui_admin'] = true;
$config['position'] = 50;



$options = array();
$option = array();

$option['option_key'] = 'enable_automatic_backups';
$option['option_key2'] = 'cronjob';
$option['name'] = 'Enable automatic backups';
$option['help'] = 'You can enable or disable the automatic backups from here.';
$option['option_value'] = '1 day';
$option['position'] = '3';
$option['field_type'] = 'dropdown';
$option['field_values'] = array( 'n' => 'No','1 day' => 'Daily','1 week' => 'Weekly','1 month' => 'Monthly');
$config['options'][] = $option;


$option = array();

$option['option_key'] = 'backups_to_keep';
$option['name'] = 'Backups to keep';
$option['help'] = 'Set the number of backups we should keep.';
$option['option_value'] = '7';
$option['position'] = '3';
$option['field_type'] = 'text';
$config['options'][] = $option;


$option = array();

$option['option_key'] = 'backup_location';
$option['name'] = 'Backup location';
$option['help'] = 'Set where the backup files should be stored.';
$option['option_value'] = 'default';
$option['position'] = '3';
$option['field_type'] = 'text';
$config['options'][] = $option;



 