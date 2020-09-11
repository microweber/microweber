<?php

$config = array();
$config['name'] = "Comments";
$config['author'] = "Microweber";
$config['ui_admin'] = true;
$config['ui_admin_iframe'] = true;
$config['ui'] = true;


$config['categories'] = "content";
$config['position'] = 16;
$config['version'] = 0.40;


$config['tables'] = array();

$config['tables']['comments'] = array(
    'rel_type' => 'text',
    'rel_id' => 'text',
    'session_id' => 'text',
    'comment_name' => 'text',
    'comment_body' => 'text',
    'comment_email' => 'text',
    'comment_website' => 'text',
    'from_url' => 'text',
    'comment_subject' => 'text',
    'is_moderated' => "integer",
    'is_spam' => "integer",
    'for_newsletter' => "integer",
    'is_new' => "integer",
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    'created_by' => 'integer',
    'edited_by' => 'integer',
    'user_ip' => 'text',
    'reply_to_comment_id' => "integer"

);


$options = array();
$option = array();

$option['option_key'] = 'email_notifcation_on_comment';
$option['option_group'] = 'comments';

$option['name'] = 'Enable email notification on new comment';
$option['help'] = 'If yes it will send you email for every new comment';
$option['option_value'] = 0;
$option['position'] = '3';
$option['field_type'] = 'dropdown';
$option['field_values'] = array('y' => 'yes', 'n' => 'no');
$config['options'][] = $option;

$option = array();

$option['option_key'] = 'enable_comments';
$option['option_group'] = 'comments';

$option['name'] = 'Allow people to post comments';
$option['help'] = 'If yes it will allow comments on your site';
$option['option_value'] = 1;
$option['position'] = '5';
$option['field_type'] = 'dropdown';
$option['field_values'] = array('y' => 'yes', 'n' => 'no');
$config['options'][] = $option;





