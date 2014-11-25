<?php

if(!defined('MW_WHITE_LABEL_SETTINGS_FILE')){
	define('MW_WHITE_LABEL_SETTINGS_FILE', __DIR__.DIRECTORY_SEPARATOR.'settings.json');
}




api_expose('save_white_label_config');
function save_white_label_config($params){
	$file = MW_WHITE_LABEL_SETTINGS_FILE;
	$params = json_encode($params);
	return file_put_contents($file,$params);   
}

function get_white_label_config(){
	$file = MW_WHITE_LABEL_SETTINGS_FILE;
	if(is_file($file)){
		$cont =  file_get_contents($file);
		$params = json_decode($cont,true);
		return $params;   
	}
}

event_bind('mw_frontend', 'make_white_label');
event_bind('mw_backend', 'make_white_label');

function make_white_label(){
	 
	$settings = get_white_label_config();
	if(isset($settings['logo_admin']) and trim($settings['logo_admin']) != ''){
		$logo_admin = $settings['logo_admin'];
		mw()->ui->admin_logo = $logo_admin; 
	}
	if(isset($settings['logo_live_edit']) and trim($settings['logo_live_edit']) != ''){
		$logo_live_edit = $settings['logo_live_edit'];
		mw()->ui->logo_live_edit = $logo_live_edit; 

	}
	if(isset($settings['logo_login']) and trim($settings['logo_login']) != ''){
		$logo_login = $settings['logo_login'];
			mw()->ui->admin_logo_login = $logo_login; 
	}
	if(isset($settings['powered_by']) and $settings['powered_by'] != false){
		$powered_by = $settings['powered_by'];
				mw()->ui->powered_by = $powered_by; 

	}
	if(isset($settings['powered_by_link']) and $settings['powered_by_link'] != false){
		$powered_by_link = $settings['powered_by_link'];
						mw()->ui->powered_by_link = $powered_by_link; 

	}
	if(isset($settings['brand_name']) and $settings['brand_name'] != false){
		$brand_name = $settings['brand_name'];
		mw()->ui->brand_name = $brand_name; 
	}
	
}

