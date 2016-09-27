<?php

$config = array();
$config['name'] = "Content Revisions";
$config['ui'] = false;
$config['ui_admin'] = false;
$config['categories'] = "other";
$config['position'] = "28";
$config['version'] = 0.05;


$config['tables'] = array();
 
$config['tables']['content_revisions_history'] = array(
                'rel_type' => 'string',
                'rel_id' => 'string',
                'field' => 'text',
                'value' => 'longText',
				'updated_at' => 'dateTime',
                'created_at' => 'dateTime',
                'created_by' => 'integer',
                'edited_by' => 'integer',
				'user_ip' => 'string',
				'checksum' => 'string',
                'session_id' => 'string',
                'url' => 'longText' 
);