<?php $layout =  get_option('data-layout', $params['id']);  
 
$params['return'] = 1;
include_once($config['path_to_module'].'../posts/index.php'); 
 
$element_in_default_file = ELEMENTS_DIR . $layout . '.php';
		$element_in_default_file = normalize_path($element_in_default_file, false);
 if(is_file($element_in_default_file)){
	include($element_in_default_file); 
 } else {
	 mw_notif( 'Click on settings to connect your layout');
 }

 