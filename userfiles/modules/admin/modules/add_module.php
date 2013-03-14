add_module


<?

if(url_param('add_module')){
	$install = url_param('add_module');
} else if(isset($_REQUEST['add_module'])){
		$install = $_REQUEST['add_module'];
}	
	 $update_api = new \mw\update();
 
	$result = $update_api -> install_module($install);
 d($result);


	