<?php

$config = array();
$config['name'] = "Picture Gallery";
$config['author'] = "Microweber";
$config['no_cache'] = true;
$config['ui'] = true;
$config['categories'] = "media";
$config['version'] = 1.11;
$config['position'] = 6;
$config['is_system'] = true;



$options = array();
$option = array();

$option['option_key'] = 'thumbnail_size';
$option['name'] = 'Thumbnail Size';
$option['help'] = 'Example: 300x200 (width x height). Leave empty for original size';
$option['option_value'] = 'original';
$option['position'] = '3';
$option['field_type'] = 'text';
//$option['field_values'] = array('y' => 'yes', 'n' => 'no');
$config['options'][] = $option;


$option = array();

$option['option_key'] = 'image_link';
$option['name'] = 'Enable image link';
$option['help'] = 'If yes, the users will be able to click on the image';
$option['option_value'] = 'y';
$option['position'] = '3';
$option['field_type'] = 'dropdown';
$option['field_values'] = array('y' => 'yes', 'n' => 'no');
$config['options'][] = $option;


