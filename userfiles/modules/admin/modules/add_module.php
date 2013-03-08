add_module


<?

if(url_param('add_module')){
	$install = url_param('add_module');
	
	 $update_api = new \mw\update();
 
	$result = $update_api -> install_module($install);
 d($result);
}

	