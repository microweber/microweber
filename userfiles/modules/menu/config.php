<?php

$config = array();
$config['name'] = "Menu";
$config['description'] = "Navigation menu for pages and links.";
$config['author'] = "Microweber";
$config['ui'] = true;
$config['ui_admin'] = true;
$config['categories'] = "navigation";
$config['position'] = 15;
$config['version'] = 0.5;



$config['tables'] = array();
$fields_to_add = array();
$fields_to_add[] = array('title', 'longText');
$fields_to_add[] = array('item_type', 'string');
$fields_to_add[] = array('parent_id', 'integereger');
$fields_to_add[] = array('content_id', 'integereger');
$fields_to_add[] = array('categories_id', 'integereger');
$fields_to_add[] = array('position', 'integereger');
$fields_to_add[] = array('updated_at', 'dateTime');
$fields_to_add[] = array('created_at', 'dateTime');
$fields_to_add[] = array('is_active', "integereger");
$fields_to_add[] = array('description', 'longText');
$fields_to_add[] = array('url', 'longText');
$config['tables']['menus'] = $fields_to_add;

