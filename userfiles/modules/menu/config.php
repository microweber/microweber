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
$fields_to_add[] = array('title', 'TEXT default NULL');
$fields_to_add[] = array('item_type', 'varchar(33) default NULL');
$fields_to_add[] = array('parent_id', 'int(11) default NULL');
$fields_to_add[] = array('content_id', 'int(11) default NULL');
$fields_to_add[] = array('categories_id', 'int(11) default NULL');
$fields_to_add[] = array('position', 'int(11) default NULL');
$fields_to_add[] = array('updated_on', 'datetime default NULL');
$fields_to_add[] = array('created_on', 'datetime default NULL');
$fields_to_add[] = array('is_active', "char(1) default 'y'");
$fields_to_add[] = array('description', 'TEXT default NULL');
$fields_to_add[] = array('url', 'TEXT default NULL');
$config['tables']['menus'] = $fields_to_add;

