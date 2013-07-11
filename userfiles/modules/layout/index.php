<?php

$set_content_type = 'post';
   $set_content_type_from_opt =  get_option('data-content-type', $params['id']);
   if( $set_content_type_from_opt != false and  $set_content_type_from_opt != ''){
	 $set_content_type  = $set_content_type_from_opt;
	 $params['content_type'] = $set_content_type;
   }
 $layout =  get_option('data-layout', $params['id']);  
  
$params['return'] = 1;
if($set_content_type != 'none'){
include_once($config['path_to_module'].'../posts/index.php'); 
}
$element_in_default_file = ELEMENTS_DIR . $layout . '.php';
		$element_in_default_file = normalize_path($element_in_default_file, false);
 if(is_file($element_in_default_file)){
	include($element_in_default_file); 
 } else {
	 mw_notif( 'Click on settings to connect your layout');
 }

 