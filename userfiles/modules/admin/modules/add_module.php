add_module


<?php

if(url_param('add_module')){
	$install = url_param('add_module');
} else if(isset($_REQUEST['add_module'])){
		$install = $_REQUEST['add_module'];
}	
	 $update_api = new \Microweber\Update();
 d($install);
	$result = $update_api -> install_module($install);
 d($result);


	