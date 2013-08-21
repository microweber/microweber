<?php

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}
if (!defined('MW_ROOTPATH')) {
    define('MW_ROOTPATH', dirname(dirname(dirname(__FILE__))) . DS);
}


if (!defined('MW_VERSION')) {
    define('MW_VERSION', 0.804);
}

if (version_compare(phpversion(), "5.3.0", "<=")) {
    exit("Error: You must have PHP version 5.3 or greater to run Microweber");
}

if (!defined('MW_SITE_URL')) {
    // please add backslash to the url if you define it
    // like http://localhost/mw/
    define('MW_SITE_URL', site_url());
}

if (!defined('MW_APP_PATH')) {
    define('MW_APP_PATH', dirname((__FILE__)) . DIRECTORY_SEPARATOR);
}
if (!defined('MW_INCLUDES_DIR')) {
    define('MW_INCLUDES_DIR', MW_APP_PATH . 'includes' . DS);
}
if (!defined('MW_INCLUDES_DIR')) {
    define('MW_INCLUDES_DIR', MW_APP_PATH . 'includes' . DS);
}
if (!defined('MW_INCLUDES_URL')) {
    define('MW_INCLUDES_URL', mw_path_to_url(MW_INCLUDES_DIR));
}
if (!defined('INCLUDES_URL')) {
    define('INCLUDES_URL', MW_INCLUDES_URL);
}
if (!defined('MW_ADMIN_VIEWS_DIR')) {
    define('MW_ADMIN_VIEWS_DIR', MW_INCLUDES_DIR . 'admin' . DS);
}


if (!defined('MW_TEMPLATES_FOLDER_NAME')) {
    define('MW_TEMPLATES_FOLDER_NAME', 'templates');
}
if (!defined('MW_USERFILES_FOLDER_NAME')) {
    define('MW_USERFILES_FOLDER_NAME', 'userfiles');
}

if (!defined('MW_CACHE_ROOT_DIR')) {
    define('MW_CACHE_ROOT_DIR', MW_ROOTPATH . 'cache' . DS);
}
if (!defined('MW_CACHE_DIR')) {
    $mw_cache_subfolder = 'mw_cache';
    if (isset($_SERVER["SERVER_NAME"])) {
        $mw_cache_subfolder = str_replace('.', '_', $_SERVER["SERVER_NAME"]);
    }
    define('MW_CACHE_DIR', MW_CACHE_ROOT_DIR . $mw_cache_subfolder . DS);
}
if (!defined('MW_USERFILES')) {
    define('MW_USERFILES', MW_ROOTPATH . MW_USERFILES_FOLDER_NAME . DS);
}
if (!defined('MW_USERFILES_URL')) {
    define("MW_USERFILES_URL", MW_SITE_URL . MW_USERFILES_FOLDER_NAME);
}
if (!defined('MW_MEDIA_URL')) {
    define("MW_MEDIA_URL", MW_USERFILES_URL . 'media/');
}

if (!defined('MW_MODULES_DIR')) {
    define("MW_MODULES_DIR", MW_USERFILES . 'modules' . DS);
}

if (!defined('MW_TEMPLATES_DIR')) {
    define('MW_TEMPLATES_DIR', MW_USERFILES . MW_TEMPLATES_FOLDER_NAME . DS);
}

if (!defined('MW_MEDIA_DIR')) {
    define('MW_MEDIA_DIR', MW_USERFILES . 'media' . DS);
}

if (!defined('MW_ELEMENTS_DIR')) {
    define('MW_ELEMENTS_DIR', MW_USERFILES . 'elements' . DS);
}
if (!defined('MW_ELEMENTS_DIR')) {
    define('MW_ELEMENTS_DIR', MW_USERFILES . 'elements' . DS);
}
if (!defined('MW_ELEMENTS_URL')) {
    define('MW_ELEMENTS_URL', MW_USERFILES_URL . 'elements/');
}
if (!defined('MW_MODULES_URL')) {
    define('MW_MODULES_URL', MW_USERFILES_URL . 'modules/');
}
if (!defined('MW_USER_IP')) {
    if (isset($_SERVER["REMOTE_ADDR"])) {
        define("MW_USER_IP", $_SERVER["REMOTE_ADDR"]);
    } else {
        define("MW_USER_IP", '127.0.0.1');
    }
}


if (!defined('MW_STORAGE_DIR')) {
    define('MW_STORAGE_DIR', MW_APP_PATH . 'storage' . DS);
}
if (!defined('T')) {
    $mtime = microtime();
    $mtime = explode(" ", $mtime);
    $mtime = $mtime[1] + $mtime[0];
    define('T', $mtime);
}
/*
* Microweber autoloader
* Loads up classes with namespaces
* Add more dicectories with set_include_path
 */
$mw_get_prev_dir = dirname(MW_APP_PATH);
set_include_path($mw_get_prev_dir . PATH_SEPARATOR .
    MW_APP_PATH . PATH_SEPARATOR .
    MW_APP_PATH . 'controllers' . DS .
    PATH_SEPARATOR . MW_MODULES_DIR .
    PATH_SEPARATOR . get_include_path());


/*set_include_path(get_include_path().PATH_SEPARATOR.
        $mw_get_prev_dir . PATH_SEPARATOR .
        MW_APP_PATH . PATH_SEPARATOR .
        MW_APP_PATH . 'controllers' . DS .
        PATH_SEPARATOR . MW_MODULES_DIR
);*/

function mw_autoload($className)
{
    $className = ltrim($className, '\\');
    $fileName = '';
    $namespace = '';

    if ($lastNsPos = strripos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }


    if ($className != '') {

        // set_include_path(  get_include_path(). PATH_SEPARATOR .MW_MODULES_DIR .strtolower($className));
        $try_module_first = MW_MODULES_DIR .$className.DS;
        $try_module_first_lower = MW_MODULES_DIR .strtolower($className).DS;

        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
//
//
        $try_file = $try_module_first.$fileName;
        $try_file2 = $try_module_first_lower.$fileName;

        include_once($fileName);


    }

}

function autoload_add($dirname)
{
    set_include_path($dirname .
        PATH_SEPARATOR . get_include_path());
}

spl_autoload_register('mw_autoload');
$_mw_registry = array();
$_mw_global_object = null;
function mw($class = null, $constructor_params = false)
{

    global $_mw_registry;
    global $_mw_global_object;
    global $application;
    if (is_object($application)) {
        $_mw_global_object = $application;
    }


    if (!is_object($_mw_global_object)) {
        $_mw_global_object = \Microweber\Application::getInstance($constructor_params);
    }
    if ($class == null or $class == false or strtolower($class) == 'application') {
        return $_mw_global_object;
    } else {
        return $_mw_global_object->$class;
    }

}

function mw_error_handler($errno, $errstr, $errfile, $errline)
{


    if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting
        return;
    }

    switch ($errno) {
        case E_USER_ERROR:
            if (!headers_sent()) {
                header("Content-Type:text/plain");

            }
            echo "<b>ERROR</b> [$errno] $errstr<br />\n";
            echo "  Fatal error on line $errline in file $errfile";
            echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
            print_r(debug_backtrace());
            echo "Aborting...<br />\n";
            exit(1);
            break;

        case E_USER_WARNING:
            echo "<b>WARNING</b> [$errno] $errstr<br />\n";
            break;

        case E_USER_NOTICE:
            // echo "<b>My NOTICE</b> [$errno] $errstr<br />\n";
            break;

        default:
            // echo "Unknown error type: [$errno] $errstr<br />\n";
            break;
    }

    /* Don't execute PHP internal error handler */
    return true;


}

//set_error_handler('mw_error_handler');
//require(MW_APP_PATH . 'classes' . DS.'mw'. DS.'_core_functions.php');

/*
 spl_autoload_register(function($className) {

 require (str_replace('\\', '/', ltrim($className, '\\')) . '.php');
 });
 */

// Basic system functions
function p($f)
{
    return __DIR__ . strtolower(str_replace('_', '/', "/$f.php"));
}

function load_file($f)
{
    return (str_replace('..', '', $f));
    //return  strtolower ( str_replace ( '_', '/', "/$f.php" ) );
}

function v(&$v, $d = NULL)
{
    return isset($v) ? $v : $d;
}


$_mw_config_file_values = array();
function _reload_c($new_config = false)
{
    global $_mw_config_file_values;

    if (defined('MW_CONFIG_FILE') and MW_CONFIG_FILE != false and is_file(MW_CONFIG_FILE)) {

        include (MW_CONFIG_FILE);
        if (isset($config)) {
            $_mw_config_file_values = $config;

        }
    }
}

function c($k, $no_static = false)
{

    if ($no_static == false) {
        global $_mw_config_file_values;
    } else {
        $_mw_config_file_values = false;
    }

    if (isset($_mw_config_file_values[$k])) {
        return $_mw_config_file_values[$k];
    } else {
        if (defined('MW_CONFIG_FILE') and MW_CONFIG_FILE != false and is_file(MW_CONFIG_FILE)) {

            //if (is_file(MW_CONFIG_FILE)) {
            include_once (MW_CONFIG_FILE);
            if (isset($config)) {
                $_mw_config_file_values = $config;
                if (isset($_mw_config_file_values[$k])) {

                    return $_mw_config_file_values[$k];
                }
            } else {
                include (MW_CONFIG_FILE);
                if (isset($config)) {
                    $_mw_config_file_values = $config;
                    if (isset($_mw_config_file_values[$k])) {

                        return $_mw_config_file_values[$k];
                    }
                }
            }
        }
        //	}
        //d(MW_CONFIG_FILE);

    }
}

function d($v)
{

    $wrap = " \n\n\ ";
    $ret = $wrap . '<pre>' . var_dump($v) . '</pre>' . $wrap;

    return $ret;
    //return dump($v);
}

$mwdbg = array();
function mwdbg($q)
{

    global $mwdbg;
    if (is_bool($q)) {

        return $mwdbg;
    } else {

        $mwdbg[] = $q;
        return $mwdbg;
    }

}


//set_error_handler('error');

function mw_error($e, $f = false, $l = false)
{
    include_once (MW_APP_PATH . 'functions' . DIRECTORY_SEPARATOR . 'language.php');

    $v = new \Microweber\View(MW_ADMIN_VIEWS_DIR . 'error.php');
    $v->e = $e;
    $v->f = $f;
    $v->l = $l;
    // _log($e -> getMessage() . ' ' . $e -> getFile());
    die($v);
}


if (!isset($site_url)) {
    $site_url = false;
}
function site_url($add_string = false)
{
    global $site_url;

    if (defined('MW_SITE_URL')) {
        $site_url = MW_SITE_URL;

    }
    if ($site_url == false) {
        $pageURL = 'http';
        if (isset($_SERVER["HTTPS"]) and ($_SERVER["HTTPS"] == "on")) {
            $pageURL .= "s";
        }

        $subdir_append = false;
        if (isset($_SERVER['PATH_INFO'])) {
            // $subdir_append = $_SERVER ['PATH_INFO'];
        } elseif (isset($_SERVER['REDIRECT_URL'])) {
            $subdir_append = $_SERVER['REDIRECT_URL'];
        } else {
            //  $subdir_append = $_SERVER ['REQUEST_URI'];
        }

        $pageURL .= "://";
        //error_log(serialize($_SERVER));
        if (isset($_SERVER["SERVER_NAME"]) and isset($_SERVER["SERVER_PORT"]) and $_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"];
        } elseif (isset($_SERVER["SERVER_NAME"])) {
            $pageURL .= $_SERVER["SERVER_NAME"];
        } else if (isset($_SERVER["HOSTNAME"])) {
            $pageURL .= $_SERVER["HOSTNAME"];
        }
        $pageURL_host = $pageURL;
        $pageURL .= $subdir_append;

        $d = '';
        if (isset($_SERVER['SCRIPT_NAME'])) {
            $d = dirname($_SERVER['SCRIPT_NAME']);
            $d = trim($d, DIRECTORY_SEPARATOR);
        }

        if ($d == '') {
            $pageURL = $pageURL_host;
        } else {

            $pageURL_host = rtrim($pageURL_host, '/') . '/';
            $d = ltrim($d, '/');
            $d = ltrim($d, DIRECTORY_SEPARATOR);

            $pageURL = $pageURL_host . $d;

        }


        //
        if (isset($_SERVER['QUERY_STRING'])) {
            $pageURL = str_replace($_SERVER['QUERY_STRING'], '', $pageURL);
        }

        if (isset($_SERVER['REDIRECT_URL'])) {
            //  $pageURL = str_replace($_SERVER ['REDIRECT_URL'], '', $pageURL);
        }

        $uz = parse_url($pageURL);
        if (isset($uz['query'])) {
            $pageURL = str_replace($uz['query'], '', $pageURL);
            $pageURL = rtrim($pageURL, '?');
        }

        $url_segs = explode('/', $pageURL);

        $i = 0;
        $unset = false;
        foreach ($url_segs as $v) {
            if ($unset == true and $d != '') {

                unset($url_segs[$i]);
            }
            if ($v == $d and $d != '') {

                $unset = true;
            }

            $i++;
        }
        $url_segs[] = '';
        $site_url = implode('/', $url_segs);

    }


    return $site_url . $add_string;
}


function mw_path_to_url($path)
{
    // var_dump($path);
    $path = str_ireplace(MW_ROOTPATH, '', $path);
    $path = str_replace('\\', '/', $path);
    $path = str_replace('//', '/', $path);
    //var_dump($path);
    return site_url($path);
}

require_once (MW_APP_PATH . 'functions' . DS . 'mw_functions.php');