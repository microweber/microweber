<?php

$config = array();
$config['name'] = "Posts List";
$config['author'] = "Microweber";
$config['no_cache'] = true;
$config['ui'] = true;
$config['categories'] = "content";
$config['version'] = 0.1;
$config['position'] = 11;
$config['is_system'] = true;
$config['options'] = array();
$config['options']['zoom'] = array("type"=>"number", "default"=> 11);
$config['options']['category'] = array("type"=>"category_tree", "default"=> '');
