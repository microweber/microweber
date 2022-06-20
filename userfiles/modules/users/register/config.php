<?php

$config = array();
$config['name'] ="Registration";
$config['author'] = "Microweber";
$config['description'] = "Microweber";
$config['website'] = "http://microweber.com/";
$config['help'] = "http://microweber.info/modules/users/registration";
$config['version'] = 0.2;
$config['ui'] = true;
$config['position'] = 33;
$config['categories'] = "users";


$options = array();
$option = array();

$option['option_key'] = 'enable_user_fb_login';
$option['name'] = 'Enable Facebook login';
$option['help'] = 'You can enable or disable login with facebook';
$option['option_value'] = 'n';
$option['position'] = '3';
$option['field_type'] = 'dropdown';
$option['field_values'] = array('y' => 'yes', 'n' => 'no');
$config['options'][] = $option;

$option = array();
$option['option_key'] = 'enable_user_twitter_login';
$option['name'] ='Enable Twitter login';
$option['help'] = 'You can enable or disable login with twitter';
$option['option_value'] = 'n';
$option['position'] = '3';
$option['field_type'] = 'dropdown';
$option['field_values'] = array('y' => 'yes', 'n' => 'no');
$config['options'][] = $option;


$option = array();
$option['option_key'] = 'enable_user_email_confirmation';
$option['name'] = 'Email confirmation required';
$option['help'] = 'If set to yes, the user will need to confirm their email address';
$option['option_value'] = 'n';
$option['position'] = '3';
$option['field_type'] = 'radio';
$option['field_values'] = array('y' => 'yes', 'n' => 'no');
$config['options'][] = $option;
