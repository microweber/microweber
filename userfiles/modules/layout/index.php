<?php

$set_content_type = 'none';
   $set_content_type_from_opt =  mw('option')->get('data-content-type', $params['id']);
   if( $set_content_type_from_opt != false and  $set_content_type_from_opt != ''){
	 $set_content_type  = $set_content_type_from_opt;
	 $params['content_type'] = $set_content_type;
   }
 $layout =  mw('option')->get('data-layout', $params['id']);  
  
$params['return'] = 1;
if($set_content_type != 'none'){
include_once($config['path_to_module'].'../posts/index.php'); 
}


if($layout == false ){
	if(isset( $params['template'])){
		 $layout = $params['template'];
	}
	
	
}
 
$layout_dot_php = $layout; 
if(!strstr($layout_dot_php,'.php')){
	$layout_dot_php = $layout_dot_php.'.php';
}
$layout_dot_php = str_replace('..','',$layout_dot_php);
$layout_indefault_file = MW_ELEMENTS_DIR . $layout_dot_php;
$layout_indefault_file = normalize_path($layout_indefault_file, false);

if(!strstr($layout_dot_php, MW_TEMPLATES_DIR)){
	$layout_intheme = MW_TEMPLATES_DIR . $layout_dot_php;

} else {
	$layout_intheme =  $layout_dot_php;
}
$layout_intheme = normalize_path($layout_intheme, false);
 

 if(is_file($layout_intheme)){
	include($layout_intheme); 
 }
  else if(is_file($layout_indefault_file)){
	include($layout_indefault_file); 
 } else {
	 mw('format')->notif( 'Click on settings to connect your layout');
 }

 