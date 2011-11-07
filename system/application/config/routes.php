<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
	/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
| 	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['scaffolding_trigger'] = 'scaffolding';
|
| This route lets you set a "secret" word that will trigger the
| scaffolding feature for added security. Note: Scaffolding must be
| enabled in the controller in which you intend to use it.   The reserved
| routes must come before any wildcard or regular expression routes.
|
*/

$route ['default_controller'] = "index";
//$route ['scaffolding_trigger'] = md5(APPPATH);

/*$exclude_controllers_autoinstall = array ('.', '..', 'index.html', 'content.php' );

$handle = (opendir ( APPPATH . 'controllers/' ));
while ( false !== ($file = readdir ( $handle )) ) {
	if (! in_array ( $file, $exclude_controllers_autoinstall )) {
		$file = str_ireplace ( ".php", "", $file );
		$installed_modules [] = $file;
	}
}
closedir ( $handle );*/

//print_r($installed_modules);

/*
foreach ( $installed_modules as $module ) {
	$route [$module] = $module;
	$route [$module . '/(.*)'] = "$module/$1";
}

$handle = (opendir ( APPPATH . 'modules/' ));
while ( false !== ($file = readdir ( $handle )) ) {
	if (! in_array ( $file, $exclude_controllers_autoinstall )) {
		if (is_dir ( APPPATH . 'modules/' . $file ) == true) {
			if (is_file ( APPPATH . 'modules/' . $file . '/disabled.php' ) == false) {
				if (is_file ( APPPATH . 'modules/' . $file . '/config/routes.php' )) {
					// include_once (APPPATH . 'modules/' . $file . '/config/routes.php');
				}
			}
		}
	}
}
closedir ( $handle );*/
//exit ();

 
//$route['((:any))'] = "index/$1";
$route ['cron/(:any)'] = "cron/index/$1";
$route ['cron'] = "cron/index";


//$route ['sql/(:any)'] = "sql/$1";
 $route ['admin/plugins/(:any)'] = "admin/plugins/$1";


$route ['admin'] = "admin/index";
$route ['admin/toolbar'] = "admin/index/toolbar";
$route ['admin/mercury'] = "admin/index/mercury";
$route ['admin/mercury/(:any)'] = "admin/index/mercury/$1";

$route ['admin/edit'] = "admin/index/edit";
$route ['admin/edit/(:any)'] = "admin/index/edit/$1";

$route ['admin/(:any)'] = "admin/index/index/$1";
 
$route ['api/module/(:any)'] = "api/module/index";
$route ['api/(:any)'] = "api/$1";


$route ['webdav'] = "webdav/index";
$route ['webdav/(:any)'] = "webdav/index/$1";

//$route ['admin/plugins'] = "admin/plugins/index";
//$route ['page.php'] = "oldpage/index/$1";

//page.php?n=26579
$route ['userbase'] = "index/userbase/";
$route ['userbase/(:any)'] = "index/userbase/$1";

$route ['users'] = "index/users/";
$route ['users/(:any)'] = "index/users/$1";
$route ['dashboard'] = "index/dashboard/";
$route ['dashboard/(:any)'] = "index/dashboard/$1";
//$route['(:any)'] = "index/index/$1";

$route ['login'] = "login/index";
$route ['login/(:any)'] = "login/$1";
$route ['fb_login'] = "fb_login/index";
$route ['fb_login/(:any)'] = "fb_login/$1";


$route ['main'] = "main/index";
$route ['main/(:any)'] = "main/$1";
$route ['ajax_helpers'] = "ajax_helpers/index";
$route ['ajax_helpers/(:any)'] = "ajax_helpers/$1";

$route ['captcha'] = "captcha";
$route ['captcha/(:any)'] = "captcha/index";



//!!!!!! MUST BE LAST
$route ['(:any)'] = "index/index/$1";
/* End of file routes.php */
/* Location: ./system/application/config/routes.php */