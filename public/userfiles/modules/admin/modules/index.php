<?php
 
if(url_param('add_module')){
	include_once($config['path_to_module'].'add_module.php');
} else if(isset($_REQUEST['add_module'])){
	include_once($config['path_to_module'].'add_module.php');
}else {
	include_once($config['path_to_module'].'installed_modules.php');
}

