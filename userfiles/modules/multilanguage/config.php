<?php
$config = array();
$config['name'] = "Multilanguage";
$config['author'] = "Bozhidar Slaveykov";
$config['ui'] = true; //if set to true, module will be visible in the toolbar
$config['ui_admin'] = true; //if set to true, module will be visible in the admin panel
$config['categories'] = "content";
$config['position'] = 99;
$config['version'] = 0.1;

$config['tables'] = array(
    'content_translations' => array(
        'id' => 'integer',
        'locale' => 'string',
        'content_id' => 'integer',
        'url' => 'string',
        'title' => 'string',
        'description' => 'string',
        'content_meta_title' => 'string',
        'content_meta_keywords' => 'string'
    ),
    'content_fields_translations' => array(
        'id' => 'integer',
        'rel_id' => 'string',
        'rel_type' => 'string',
        'locale' => 'string',
        'field' => 'string',
        'value' => 'string',
    )
);