<?php

$config = array();
$config['name'] ="Dynamic Text";
$config['author'] = "Microweber";
$config['no_cache'] = false;
$config['ui'] = true;
$config['ui_admin'] = true;
$config['categories'] = "forms";
$config['version'] = 0.06;
$config['position'] = 10;

$config['tables'] = array(
    'dynamic_text_variables' => array(
        'id' => 'integer',
        'variable' => 'string',
        'content' => 'text'
    )
);
