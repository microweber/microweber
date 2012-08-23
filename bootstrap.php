<?php

defined('T') OR die();
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


//error_reporting ( E_ALL  );
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



  |



  | EXT		- The file extension.  Typically ".php"



  | FCPATH	- The full server path to THIS file



  | SELF		- The name of THIS file (typically "index.php)



  | BASEPATH	- The full server path to the "system" folder



  | APPPATH	- The full server path to the "application" folder



  |



 */

define('EXT', '.' . pathinfo(__FILE__, PATHINFO_EXTENSION));

define('FCPATH', __FILE__);

define('ROOTPATH', dirname(__FILE__));

define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

define('BASEPATH', $system_folder . '/');

define('BASEPATHSTATIC', ROOTPATH . '/static/');

#define ( 'BASEPATHCONTENT', BASEPATH . 'content/' );


define('TABLE_PREFIX', 'firecms_');

define('USERFILES_DIRNAME', 'userfiles');

define('USERFILES', ROOTPATH . '/' . USERFILES_DIRNAME . '/');




define("USERFILES_URL", site_url('userfiles/'));




define("USERFILES_DIR", USERFILES);



define("MODULES_DIR", USERFILES . 'modules/');


define('TEMPLATEFILES_DIRNAME', 'templates');

define('TEMPLATEFILES', USERFILES . TEMPLATEFILES_DIRNAME . '/');

define('MEDIAFILES', USERFILES . 'media' . '/');


define('ELEMENTS_DIR', USERFILES . 'elements' . '/');

define('STYLES_DIR', USERFILES . 'styles' . '/');


define('PLUGINS_DIRNAME', USERFILES . 'plugins' . '/');

define("USER_IP", $_SERVER ["REMOTE_ADDR"]);
define("DS", DIRECTORY_SEPARATOR);





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

$md5_conf = md5(serialize($mw_config));
$cache_main_dir = dirname((__FILE__)) . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . $md5_conf . DIRECTORY_SEPARATOR;

if (is_dir($cache_main_dir) == false) {

    @mkdir($cache_main_dir);
}

$cache_main_dir = $cache_main_dir . md5(ROOTPATH) . DIRECTORY_SEPARATOR;

if (is_dir($cache_main_dir) == false) {

    @mkdir($cache_main_dir);
}

define('CACHEDIR', $cache_main_dir);
define('SITE_URL', SITEURL);


define('HISTORY_DIR', CACHEDIR . 'history' . '/');

define('CACHE_FILES_EXTENSION', '.php');

define('CACHE_CONTENT_PREPEND', '<?php exit(); ?>');

define('CACHEDIR_ROOT', dirname((__FILE__)) . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR);

define('DATETIME_FORMAT', 'F j g:m a');

define('APPPATH', $application_folder . DIRECTORY_SEPARATOR);
define('APPPATH_FULL', ROOTPATH . DIRECTORY_SEPARATOR . APPPATH); //full filesystem path





define('LIBSPATH', APPPATH . 'libraries' . DIRECTORY_SEPARATOR);
define('ADMIN_URL', SITEURL . 'admin');





define('INCLUDES_PATH', ROOTPATH . DIRECTORY_SEPARATOR . APPPATH . 'includes' . DS); //full filesystem path
define('INCLUDES_DIR', INCLUDES_PATH); //full filesystem path
define('INCLUDES_URL', SITEURL . $application_folder . '/includes/'); //full filesystem path
define('VIEWSPATH', INCLUDES_PATH . 'admin' . DS); //full filesystem path
define('ADMIN_VIEWS_PATH', INCLUDES_PATH . 'admin' . DS); //full filesystem path
define('ADMIN_VIEWS_URL', INCLUDES_URL . 'admin');









$media_url = SITEURL;

$media_url = $media_url . USERFILES_DIRNAME . '/media/';

define('MEDIA_URL', $media_url);

$media_url = SITEURL . USERFILES_DIRNAME . '/elements/';

define('ELEMENTS_URL', $media_url);

$media_url = SITEURL . USERFILES_DIRNAME . '/resources/';

define('RESOURCES_URL', $media_url);


$media_url = SITEURL . USERFILES_DIRNAME . '/modules/';

define('MODULES_URL', $media_url);

$media_url = SITEURL . USERFILES_DIRNAME . '/styles/';

define('STYLES_URL', $media_url);



define('RESOURCES_DIR', USERFILES . 'resources' . '/');
////var_dump( ADMIN_STATIC_FILES_URL);


/*



  |---------------------------------------------------------------



  | LOAD THE FRONT CONTROLLER



  |---------------------------------------------------------------



  |



  | And away we go...



  |



 */

ini_set('include_path', ini_get('include_path') . ':' . BASEPATH . 'libraries/');

if (defined('NO_MICROWEBER') == false) {

    //rm(($file));
    //require_once (APPPATH . 'models/system_loader.php');
}

function site_url($add_string = false) {
    static $u1;
    if ($u1 == false) {
        $pageURL = 'http';
        if (isset($_SERVER ["HTTPS"]) and ($_SERVER ["HTTPS"] == "on")) {
            $pageURL .= "s";
        }
        $pageURL .= "://";
        if ($_SERVER ["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER ["SERVER_NAME"] . ":" . $_SERVER ["SERVER_PORT"] . $_SERVER ["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER ["SERVER_NAME"] . $_SERVER ["REQUEST_URI"];
        }

        if (isset($_SERVER ['SCRIPT_NAME'])) {
            $d = dirname($_SERVER ['SCRIPT_NAME']);
            $d = trim($d, '/');
        }
        $url_segs = explode('/', $pageURL);
        $i = 0;
        $unset = false;
        foreach ($url_segs as $v) {
            if ($unset == true) {
                unset($url_segs [$i]);
            }
            if ($v == $d) {

                $unset = true;
            }

            $i++;
        }
        $url_segs [] = '';
        $u1 = implode('/', $url_segs);
    }
    return $u1 . $add_string;
}