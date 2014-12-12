<?php
$config = array();
$config['name'] = "Testimonials";
$config['author'] = "Microweber";
$config['ui'] = true; //if set to true, module will be visible in the toolbar
$config['ui_admin'] = true; //if set to true, module will be visible in the admin panel
$config['categories'] = "content";
$config['position'] = 99;
$config['version'] = 0.1;

$config['tables'] = array(
    "testimonials" => array(
        'id' => "integer",
        'name' => "text",
        'content' => "text",
        'read_more_url' => "text",
        'created_at' => "datetime",
        'position' => "integer"
    )
);
