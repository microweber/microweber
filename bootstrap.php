<?php defined('T') OR die();
/*
 * EDIT THIS FILE TO SETUP SYSTEM STATE
 * If posible, these should be set in the php.ini instead of here!
 */

/*
 * Set the server timezone
 * see: http://us3.php.net/manual/en/timezones.php
 */
//date_default_timezone_set("America/Chicago");

/*
 * Set everything to UTF-8
 */
//setlocale(LC_ALL, 'en_US.utf-8');
//iconv_set_encoding("internal_encoding", "UTF-8");
//mb_internal_encoding('UTF-8');
$mw_config = array ();
  
 

$mw_config ['site_url'] = 'http://pecata/Microweber/';   //use slash at the end

 $mw_config ['system_folder'] = 'application/ci';
$mw_config ['application_folder'] = 'application';
 
$mw_config ['db_hostname'] = 'localhost';

$mw_config ['db_username'] = 'root';

$mw_config ['db_password'] = '123456';  

 
$mw_config ['db_database'] = 'digi2';

ini_set('display_errors', '1');
 

/*
 * PHP error settings (Report all PHP errors)
 */
//error_reporting(E_ALL);
//ini_set('display_errors','On'); 



if(!defined('mw_curent_url')){
function mw_curent_url() {
	
	$pageURL = 'http';
	
	if (isset ( $_SERVER ["HTTPS"] )) { 
		
		if ($_SERVER ["HTTPS"] == "on") {
			
			$pageURL .= "s";
		
		}
	
	}
	
	$pageURL .= "://";
	
	if ($_SERVER ["SERVER_PORT"] != "80") {
		
		$pageURL .= $_SERVER ["SERVER_NAME"] . ":" . $_SERVER ["SERVER_PORT"] . $_SERVER ["REQUEST_URI"];
	
	} else {
		
		$pageURL .= $_SERVER ["SERVER_NAME"] . $_SERVER ["REQUEST_URI"];
	
	}
	
	return $pageURL;

}
}
$the_curent_link123 = mw_curent_url ();

$the_curent_link123 = strtolower ( $the_curent_link123 );

/*



|---------------------------------------------------------------



| PHP ERROR REPORTING LEVEL



|---------------------------------------------------------------



|



| By default CI runs with error reporting set to ALL.  For security



| reasons you are encouraged to change this when your site goes live.



| For more info visit:  http://www.php.net/error_reporting



|



*/

//error_reporting(E_ALL);


if (! defined ( 'E_STRICT' )) {
	
	define ( E_STRICT, 0 );

}

//

//error_reporting ( E_ALL & ~ E_STRICT );


/*



|---------------------------------------------------------------



| SYSTEM FOLDER NAME



|---------------------------------------------------------------



|



| This variable must contain the name of your "system" folder.



| Include the path if the folder is not in the same  directory



| as this file.



|



| NO TRAILING SLASH!



|



*/

$system_folder = $mw_config ['system_folder'];

/*



|---------------------------------------------------------------



| APPLICATION FOLDER NAME



|---------------------------------------------------------------



|



| If you want this front controller to use a different "application"



| folder then the default one you can set its name here. The folder



| can also be renamed or relocated anywhere on your server.



| For more info please see the user guide:



| http://codeigniter.com/user_guide/general/managing_apps.html



|



|



| NO TRAILING SLASH!



|



*/

$application_folder = $mw_config ['application_folder'];

/*



|===============================================================



| END OF USER CONFIGURABLE SETTINGS



|===============================================================



*/

/*



|---------------------------------------------------------------



| SET THE SERVER PATH



|---------------------------------------------------------------



|



| Let's attempt to determine the full-server path to the "system"



| folder in order to reduce the possibility of path problems.



| Note: We only attempt this if the user hasn't specified a



| full server path.



|



*/
if (isset ( $mw_config ['system_folder_shared'] )) {
	if ($mw_config ['system_folder_shared'] == false) {
		
		if (strpos ( $system_folder, '/' ) === FALSE) {
			if (function_exists ( 'realpath' ) and @realpath ( dirname ( __FILE__ ) ) !== FALSE) {
				$system_folder = realpath ( dirname ( __FILE__ ) ) . '/' . $system_folder;
			}
		
		} else {
			// Swap directory separators to Unix style for consistency
			$system_folder = str_replace ( "\\", "/", $system_folder );
		
		}
	} else {
		$system_folder = $mw_config ['system_folder_shared'];
	}
}
/*



|---------------------------------------------------------------



| DEFINE APPLICATION CONSTANTS



|---------------------------------------------------------------



|



| EXT		- The file extension.  Typically ".php"



| FCPATH	- The full server path to THIS file



| SELF		- The name of THIS file (typically "index.php)



| BASEPATH	- The full server path to the "system" folder



| APPPATH	- The full server path to the "application" folder



|



*/

define ( 'EXT', '.' . pathinfo ( __FILE__, PATHINFO_EXTENSION ) );

define ( 'FCPATH', __FILE__ );

define ( 'ROOTPATH', dirname ( __FILE__ ) );

define ( 'SELF', pathinfo ( __FILE__, PATHINFO_BASENAME ) );

define ( 'BASEPATH', $system_folder . '/' );

define ( 'BASEPATHSTATIC', ROOTPATH . '/static/' );

#define ( 'BASEPATHCONTENT', BASEPATH . 'content/' );


define ( 'TABLE_PREFIX', 'firecms_' );

define ( 'USERFILES_DIRNAME', 'userfiles' );

define ( 'USERFILES', ROOTPATH . '/' . USERFILES_DIRNAME . '/' );

define ( 'TEMPLATEFILES_DIRNAME', 'templates' );

define ( 'TEMPLATEFILES', USERFILES . TEMPLATEFILES_DIRNAME . '/' );

define ( 'MEDIAFILES', USERFILES . 'media' . '/' );


define ( 'ELEMENTS_DIR', USERFILES . 'elements' . '/' );

define ( 'STYLES_DIR', USERFILES . 'styles' . '/' );


define ( 'PLUGINS_DIRNAME', USERFILES . 'plugins' . '/' );

define ( 'DBHOSTNAME', $mw_config ['db_hostname'] );

define ( 'DBUSERNAME', $mw_config ['db_username'] );

define ( 'DBPASSWORD', $mw_config ['db_password'] );

define ( 'DBDATABASE', $mw_config ['db_database'] );

define ( "USER_IP", $_SERVER ["REMOTE_ADDR"] );
define ( "DS", DIRECTORY_SEPARATOR );





$subdir = $_SERVER ['SCRIPT_NAME'];

$subdir = dirname ( $subdir );

$subdir = ltrim ( $subdir, '/' );

$subdir = rtrim ( $subdir, '/' );

$get_url_dir = $_SERVER ["SERVER_NAME"] . (trim ( $_SERVER ["REQUEST_URI"] ));
//var_Dump( $_SERVER);
//define ( 'SITEURL', 'http://' . $_SERVER ["SERVER_NAME"] . '/' . $subdir . '/' );


$pageURL = 'http';

if (isset ( $_SERVER ["HTTPS"] )) {
	
	if ($_SERVER ["HTTPS"] == "on") {
		
		$pageURL .= "s";
	
	}

}
if ($mw_config ['site_url']) {
 // define ( 'SITEURL', $pageURL . '://' . $mw_config ['site_url'] . '/' . $subdir . '/' );
 define ( 'SITEURL', $mw_config ['site_url'] );
} else {
  define ( 'SITEURL', $pageURL . '://' . $_SERVER ["SERVER_NAME"] . '/' . $subdir . '/' );
}

$md5_conf = md5(serialize($mw_config));
$cache_main_dir = dirname ( (__FILE__) ) . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR.$md5_conf. DIRECTORY_SEPARATOR;

if (is_dir ( $cache_main_dir ) == false) {
	
	@mkdir ( $cache_main_dir );

}

$cache_main_dir = $cache_main_dir . md5 ( ROOTPATH ) . DIRECTORY_SEPARATOR;

if (is_dir ( $cache_main_dir ) == false) {
	
	@mkdir ( $cache_main_dir );

}

define ( 'CACHEDIR', $cache_main_dir );
define ( 'SITE_URL', SITEURL ); 


define ( 'HISTORY_DIR', CACHEDIR . 'history' . '/' );

define ( 'CACHE_FILES_EXTENSION', '.php' );

define ( 'CACHE_CONTENT_PREPEND', '<?php exit(); ?>' );

define ( 'CACHEDIR_ROOT', dirname ( (__FILE__) ) . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR );

define ( 'DATETIME_FORMAT', 'F j g:m a' );

define ( 'APPPATH', $application_folder . DIRECTORY_SEPARATOR );
define ( 'APPPATH_FULL', ROOTPATH. DIRECTORY_SEPARATOR. APPPATH  ); //full filesystem path

define ( 'LIBSPATH', APPPATH . 'libraries' . DIRECTORY_SEPARATOR  );
define ( 'VIEWSPATH', APPPATH . '/views/' ); //full filesystem path  
define ( 'ADMINVIEWSPATH', APPPATH . 'views/admin/' ); //full filesystem path  
define ( 'ADMIN_URL', SITEURL . 'admin' );
define ( 'ADMIN_STATIC_FILES_URL', SITEURL . '/' . $application_folder . '/views/admin/static/' );
define ( 'ADMIN_STATIC_FILES_RELATIVE_URL',   $application_folder . '/views/admin/static/' );








define ( 'INCLUDES_PATH', ROOTPATH. DIRECTORY_SEPARATOR. APPPATH . 'includes/' ); //full filesystem path
define ( 'INCLUDES_DIR', INCLUDES_PATH); //full filesystem path
define ( 'INCLUDES_URL', SITEURL . $application_folder. '/includes/' ); //full filesystem path  
 



$media_url = SITEURL;

$media_url = $media_url . USERFILES_DIRNAME . '/media/';

define ( 'MEDIA_URL', $media_url );

$media_url = SITEURL .  USERFILES_DIRNAME . '/elements/';

define ( 'ELEMENTS_URL', $media_url ); 

$media_url = SITEURL . USERFILES_DIRNAME . '/resources/';
 
define ( 'RESOURCES_URL', $media_url );


$media_url = SITEURL . USERFILES_DIRNAME . '/modules/';
 
define ( 'MODULES_URL', $media_url );

$media_url = SITEURL .  USERFILES_DIRNAME . '/styles/';

 define ( 'STYLES_URL', $media_url);



define ( 'RESOURCES_DIR', USERFILES . 'resources' . '/' );
////var_dump( ADMIN_STATIC_FILES_URL);  

    
/*



|---------------------------------------------------------------



| LOAD THE FRONT CONTROLLER



|---------------------------------------------------------------



|



| And away we go...



|



*/

ini_set ( 'include_path', ini_get ( 'include_path' ) . ':' . BASEPATH . 'libraries/' );

if (defined ( 'NO_MICROWEBER' ) == false) {
	
	//rm(($file));
	//require_once (APPPATH . 'models/system_loader.php');
	 

}

