<?php

defined('T') OR die();


if (!ini_get('safe_mode')) {
    set_time_limit(60);
}


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
$mw_config = array();



$mw_config ['site_url'] = site_url();   //use slash at the end

$mw_config ['system_folder'] = 'application';
$mw_config ['application_folder'] = 'application';





session_set_cookie_params(86400); //Sets the session cookie lifetime to 12 hours.



if (!defined('E_STRICT')) {

    define(E_STRICT, 0);
}

//
//error_reporting ( E_ALL & ~ E_STRICT );
error_reporting ( E_ALL  );
$system_folder = $mw_config ['system_folder'];



$application_folder = $mw_config ['application_folder'];

if (isset($mw_config ['system_folder_shared'])) {
    if ($mw_config ['system_folder_shared'] == false) {

        if (strpos($system_folder, '/') === FALSE) {
            if (function_exists('realpath') and @realpath(dirname(__FILE__)) !== FALSE) {
                $system_folder = realpath(dirname(__FILE__)) . '/' . $system_folder;
            }
        } else {
            // Swap directory separators to Unix style for consistency
            $system_folder = str_replace("\\", "/", $system_folder);
        }
    } else {
        $system_folder = $mw_config ['system_folder_shared'];
    }
}
/*
 
  |---------------------------------------------------------------
  | DEFINE APPLICATION CONSTANTS
 
 |---------------------------------------------------------------
 


 */

//define('EXT', '.' . pathinfo(__FILE__, PATHINFO_EXTENSION));

define("DS", DIRECTORY_SEPARATOR);
//define('FCPATH', __FILE__);

define('MW_ROOTPATH', dirname(__FILE__));

define('MW_SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

define('MW_BASEPATH', $system_folder . '/');

define('MW_BASEPATHSTATIC', MW_ROOTPATH . '/static/');

#define ( 'MW_BASEPATHCONTENT', MW_BASEPATH . 'content/' );


define('MW_TABLE_PREFIX', 'firecms_');

define('MW_USERFILES_DIRNAME', 'userfiles');

define('MW_USERFILES', MW_ROOTPATH . DS . MW_USERFILES_DIRNAME . DS);




define("MW_USERFILES_URL", site_url('userfiles/'));



define("MW_USERFILES_DIR", MW_USERFILES);



define("MODULES_DIR", MW_USERFILES . 'modules/');


define('TEMPLATEFILES_DIRNAME', 'templates');

define('TEMPLATEFILES', MW_USERFILES . TEMPLATEFILES_DIRNAME . DS);

define('MEDIAFILES', MW_USERFILES . 'media' . '/');


define('ELEMENTS_DIR', MW_USERFILES . 'elements' . '/');

define('STYLES_DIR', MW_USERFILES . 'styles' . '/');


define('PLUGINS_DIRNAME', MW_USERFILES . 'plugins' . '/');

define("USER_IP", $_SERVER ["REMOTE_ADDR"]);






$subdir = $_SERVER ['SCRIPT_NAME'];

$subdir = dirname($subdir);

$subdir = ltrim($subdir, '/');

$subdir = rtrim($subdir, '/');

$get_url_dir = $_SERVER ["SERVER_NAME"] . (trim($_SERVER ["REQUEST_URI"]));
//var_Dump( $_SERVER);
//define ( 'SITEURL', 'http://' . $_SERVER ["SERVER_NAME"] . '/' . $subdir . '/' );


$pageURL = 'http';

if (isset($_SERVER ["HTTPS"])) {

    if ($_SERVER ["HTTPS"] == "on") {

        $pageURL .= "s";
    }
}
if ($mw_config ['site_url']) {
    // define ( 'SITEURL', $pageURL . '://' . $mw_config ['site_url'] . '/' . $subdir . '/' );
    define('SITEURL', $mw_config ['site_url']);
} else {
    define('SITEURL', $pageURL . '://' . $_SERVER ["SERVER_NAME"] . '/' . $subdir . '/');
}
$dnf = MW_ROOTPATH;
$md5_conf = 'mw_cache_' . crc32($dnf.SITEURL);
$cache_main_dir = $dnf . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . $md5_conf . DIRECTORY_SEPARATOR;

if (is_dir($cache_main_dir) == false) {

    @mkdir($cache_main_dir);
}

//$cache_main_dir = $cache_main_dir . crc32(MW_ROOTPATH) . DIRECTORY_SEPARATOR;

if (is_dir($cache_main_dir) == false) {

    @mkdir($cache_main_dir);
}

define('CACHEDIR', $cache_main_dir);
define('SITE_URL', SITEURL);


define('HISTORY_DIR', CACHEDIR . 'history' . DIRECTORY_SEPARATOR);

define('CACHE_FILES_EXTENSION', '.php');

define('CACHE_CONTENT_PREPEND', '<?php exit(); ?>');

define('CACHEDIR_ROOT', dirname((__FILE__)) .  'cache' . DIRECTORY_SEPARATOR);

define('DATETIME_FORMAT', 'F j g:m a');

define('MW_APPPATH', $application_folder . DIRECTORY_SEPARATOR);
define('MW_APPPATH_FULL', MW_ROOTPATH . DIRECTORY_SEPARATOR . MW_APPPATH); //full filesystem path





define('LIBSPATH', MW_APPPATH . 'libraries' . DIRECTORY_SEPARATOR);
define('DBPATH', 'db' . DS);
define('DBPATH_FULL', MW_ROOTPATH . DIRECTORY_SEPARATOR . DBPATH);

define('ADMIN_URL', SITEURL . 'admin');





define('INCLUDES_PATH', MW_ROOTPATH . DIRECTORY_SEPARATOR . MW_APPPATH . 'includes' . DS); //full filesystem path
define('INCLUDES_DIR', INCLUDES_PATH); //full filesystem path
define('INCLUDES_URL', SITEURL . $application_folder . '/includes/'); //full filesystem path
define('VIEWSPATH', INCLUDES_PATH . 'admin' . DS); //full filesystem path
define('ADMIN_VIEWS_PATH', INCLUDES_PATH . 'admin' . DS); //full filesystem path
define('ADMIN_VIEWS_URL', INCLUDES_URL . 'admin');











$media_url = SITEURL;

$media_url = $media_url . MW_USERFILES_DIRNAME . '/media/';

define('MEDIA_URL', $media_url);

$media_url = SITEURL . MW_USERFILES_DIRNAME . '/elements/';

define('ELEMENTS_URL', $media_url);

$media_url = SITEURL . MW_USERFILES_DIRNAME . '/resources/';

define('RESOURCES_URL', $media_url);


$media_url = SITEURL . MW_USERFILES_DIRNAME . '/modules/';

define('MODULES_URL', $media_url);

$media_url = SITEURL . MW_USERFILES_DIRNAME . '/styles/';

define('STYLES_URL', $media_url);



define('RESOURCES_DIR', MW_USERFILES . 'resources' . '/');
////var_dump( ADMIN_STATIC_FILES_URL);


/*



  |---------------------------------------------------------------



  | LOAD THE FRONT CONTROLLER



  |---------------------------------------------------------------



  |



  | And away we go...



  |



 */

ini_set('include_path', ini_get('include_path') . ':' . MW_BASEPATH . 'libraries/');

if (defined('NO_MICROWEBER') == false) {

    //rm(($file));
    //require_once (MW_APPPATH . 'models/system_loader.php');
}

function site_url($add_string = false) {
    static $u1;
    if ($u1 == false) {
        $pageURL = 'http';
        if (isset($_SERVER ["HTTPS"]) and ($_SERVER ["HTTPS"] == "on")) {
            $pageURL .= "s";
        }

        $subdir_append = false;
        if (isset($_SERVER ['PATH_INFO'])) {
            // $subdir_append = $_SERVER ['PATH_INFO'];
        } elseif (isset($_SERVER ['REDIRECT_URL'])) {
            $subdir_append = $_SERVER ['REDIRECT_URL'];
        } else {
            //  $subdir_append = $_SERVER ['REQUEST_URI'];
        }


        $pageURL .= "://";
        if ($_SERVER ["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER ["SERVER_NAME"] . ":" . $_SERVER ["SERVER_PORT"];
        } else {
            $pageURL .= $_SERVER ["SERVER_NAME"];
        }
        $pageURL_host = $pageURL;
        $pageURL .= $subdir_append;

        $d = '';
        if (isset($_SERVER ['SCRIPT_NAME'])) {
            $d = dirname($_SERVER ['SCRIPT_NAME']);
            $d = trim($d, '/');
        }

        if ($d == '') {
            $pageURL = $pageURL_host;
        } else {
            $pageURL = $pageURL_host.'/'.$d;
        }
       // var_dump($d);
        if (isset($_SERVER ['QUERY_STRING'])) {
            $pageURL = str_replace($_SERVER ['QUERY_STRING'], '', $pageURL);
        }



        if (isset($_SERVER ['REDIRECT_URL'])) {
            //  $pageURL = str_replace($_SERVER ['REDIRECT_URL'], '', $pageURL);
        }






        $uz = parse_url($pageURL);
        if (isset($uz['query'])) {
            $pageURL = str_replace($uz['query'], '', $pageURL);
            $pageURL = rtrim($pageURL, '?');
        }

//var_Dump($_SERVER);
        //$url_segs1 = str_replace($pageURL_host, '',$pageURL);
        $url_segs = explode('/', $pageURL);
// 	 var_dump($d);
// var_dump($pageURL);
//		  var_dump($_SERVER);
//        exit;
        $i = 0;
        $unset = false;
        foreach ($url_segs as $v) {
            if ($unset == true and $d != '') {

                unset($url_segs [$i]);
            }
            if ($v == $d and $d != '') {

                $unset = true;
            }

            $i++;
        }
        $url_segs [] = '';
        $u1 = implode('/', $url_segs);
    }
    //var_Dump($u1);
    return $u1 . $add_string;
}