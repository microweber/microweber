<?php
/*
 * Templating engine for KFM
 */
function kfm_parse_template($template){
	$res=preg_match_all('/{\$([^.}]*)[\.\s]?([^}]*)}/', $template,$matches,PREG_SET_ORDER);
	foreach($matches as $match){
		$function='kfm_template_function_'.$match[1];
		$template=str_replace($match[0],call_user_func_array($function,str_replace('__space__',' ',preg_split('/[\s]+/',str_replace('\ ','__space__',$match[2])))),$template);
	}
	return $template;
}
function kfm_template_function_settings($name){
	global $kfm;
	return $kfm->setting($name);
}
function kfm_template_function_setting(){
	$args=func_get_args();
	return call_user_func_array('kfm_template_function_settings',$args);
}
function kfm_template_function_lang(){
	$args=func_get_args();
	return call_user_func_array('kfm_lang',$args);
}

?>
