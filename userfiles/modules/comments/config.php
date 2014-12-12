<?php

$config = array();
$config['name'] = "Comments";
$config['author'] = "Microweber";
$config['ui_admin'] = true;
$config['ui_admin_iframe'] = true;
$config['ui'] = true;


$config['categories'] = "content";
$config['position'] = 10;
$config['version'] = 0.33;


 //$config['tables'] = array();
 //$fields_to_add = array();
//$fields_to_add[] = array('rel_type', 'longText');
//$fields_to_add[] = array('rel_id', 'longText');
//$fields_to_add[] = array('updated_at', 'dateTime');
//$fields_to_add[] = array('created_at', 'dateTime');
//$fields_to_add[] = array('created_by', 'integereger');
//$fields_to_add[] = array('edited_by', 'integereger');
//$fields_to_add[] = array('comment_name', 'longText');
//$fields_to_add[] = array('comment_body', 'longText');
//$fields_to_add[] = array('comment_email', 'longText');
//$fields_to_add[] = array('comment_website', 'longText');
//$fields_to_add[] = array('is_moderated', "integereger");
//$fields_to_add[] = array('from_url', 'longText');
//$fields_to_add[] = array('comment_subject', 'longText');
//
//
//$fields_to_add[] = array('is_new', "integereger");
//
//$fields_to_add[] = array('for_newsletter', "integereger");
//$fields_to_add[] = array('session_id', 'string');
//$config['tables']['comments'] = $fields_to_add;



$options = array();
$option = array();

$option['option_key'] = 'email_notifcation_on_comment';
$option['option_group'] = 'comments';

$option['name'] = 'Enable email notification on new comment';
$option['help'] = 'If yes it will send you email for every new comment';
$option['option_value'] = 'n';
$option['position'] = '3';
$option['field_type'] = 'dropdown';
$option['field_values'] = array('y' => 'yes', 'n' => 'no');
$config['options'][] = $option;

$option = array();

$option['option_key'] = 'enable_comments';
$option['option_group'] = 'comments';

$option['name'] = 'Allow people to post comments';
$option['help'] = 'If yes it will allow comments on your site';
$option['option_value'] = 'y';
$option['position'] = '5';
$option['field_type'] = 'dropdown';
$option['field_values'] = array('y' => 'yes', 'n' => 'no');
$config['options'][] = $option;





