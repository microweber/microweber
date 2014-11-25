<?php

 
event_bind('site_header', 'append_global_styles_site_header');

 function append_global_styles_site_header($params) {
	//print '';
	
	 $template_name = $params;
	 $template_name = str_replace('..','',$template_name);
	
 $url = api_link('user_styles_css/?template_name='.$template_name);
	  $src = '<link rel="stylesheet" id="mw-user-stylesheet" href="' . $url . '" type="text/css" media="all">' . "\n";
    template_head( $src);
 
}
api_expose('user_styles_css');
 function user_styles_css($params) {
	 if(!is_array($params)){
	  $template_name = $params;
	 } else {
		extract($params); 
	 }
	 if(!isset( $template_name)){
		exit(); 
	 }
	 
	 
	 $template_name = str_replace('..','',$template_name);
	 if (defined('TEMPLATE_NAME') == false) {

        define('TEMPLATE_NAME', $template_name);
    }

	 $custom_fn = TEMPLATES_DIR.$template_name;
	 // d( $custom_fn);
	 if(is_dir( $custom_fn)){
		  $custom_fn = $custom_fn.DS.'global_styles.php';
		  $custom_fn = normalize_path( $custom_fn,false);
		  if(is_file($custom_fn)){
			
			header("Content-type: text/css", true);
			include($custom_fn);
			exit();  
		  }
		 //d( $custom_fn);
	 }
	 
 
}
event_bind('mw_after_editor_toolbar', 'global_styles_editor_btn_insert');

function global_styles_editor_btn_insert() {
	//print '';
	
	 print '<module type="editor/global_styles" view="editor" id="mw-editor-global-styles-btn" />';
	
 
}

 