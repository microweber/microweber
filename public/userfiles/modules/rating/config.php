<?php

$config = array();
$config['name'] = "Rating";
$config['author'] = "Microweber";
$config['description'] = "Microweber";
$config['website'] = "http://microweber.com/"; 
$config['help'] = "http://microweber.com"; 
$config['version'] = 0.1;
$config['ui_admin'] = false;
$config['ui'] = false;
$config['categories'] = "content";
$config['position'] = 100;


$config['tables'] = [
    'rating' => [
        'rel_type' => 'string',
        'rel_id' => 'string',
        'rating' => 'integer',
        'comment' => 'text',
        'updated_at' => 'dateTime',
        'created_at' => 'dateTime',
        'created_by' => 'integer',
        'edited_by' => 'integer',
        'session_id' => 'string',
    ],
];