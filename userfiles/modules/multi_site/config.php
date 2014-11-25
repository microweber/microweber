<?php

$config = array();
$config['name'] = "Multi site";
$config['author'] = "Microweber";
$config['description'] = "Multi site manager";
$config['website'] = "http://microweber.com/"; 
$config['help'] = "http://microweber.com"; 
$config['version'] = 0.1;
$config['ui'] = true;
$config['ui_admin'] = true;
$config['categories'] = "admin";
$config['position'] = 100;


$sites_table = array(
    'domain' =>'text',
    'title' =>'text',
    'username' =>'text',
    'password' =>'text',
    'email' =>'text',
    'created_on' =>'datetime',
    'created_on' =>'updated_on',
    'user_id' =>'int',
    'created_by' =>'int',
    'edited_by' =>'int',
    'user_ip' =>'text'
);
$tables = array(
    'sites' =>$sites_table
);
$config['tables'] = $tables;
