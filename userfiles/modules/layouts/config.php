<?php
$config = array();
$config['name'] = "Layouts";
$config['author'] = "Microweber";
$config['ui'] = false; //if set to true, module will be visible in the toolbar
$config['ui_admin'] = false; //if set to true, module will be visible in the admin panel
$config['categories'] = "content";
$config['position'] = 99;
$config['version'] = 0.1;

$config['tables'] = array(

);


$config['settings'][]  = array(
    'type'=>'tooltip',
    'title'=>'Spacing',
    'icon'=>'mw-icon-wand',
    'view'=>'quick_settings',
);
//$config['settings'][]  = array(
//    'type'=>'tooltip',
//    'title'=>'Skin',
//    'icon'=>'mw-icon-wand',
//    'module-name'=>'layouts',
//    'view'=>'quick_skin_change',
//);
