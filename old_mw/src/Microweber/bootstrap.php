<?php
if (version_compare(phpversion(), "5.3.0", "<=")) {
    exit("Error: You must have PHP version 5.3 or greater to run Microweber");
}


if (!defined('MW_VERSION')) {
    define('MW_VERSION', 0.951);
}

/**
 * This file will bootstrap Microweber by:
 *  - Loading vendor/autoload.php file if exists
 *  - Registering PSR Autoloader
 *  - Defining constants if they are not set
 *  - Including common functions files
 *
 * If you need to customize the defaults,
 * please create a custom bootstrap file and include it in index.php before loading this file
 *
 */

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

if (!defined('MW_ROOTPATH')) {
    define('MW_ROOTPATH', dirname(dirname(dirname(__FILE__))) . DS);
}
if (!defined('MW_CONFIG_FILE')) {
    $bootstrap_file_for_site = false;
    if (!isset($_SERVER["SERVER_NAME"])) {
        $config_file_for_site = MW_ROOTPATH . 'config_localhost' . '.php';
        $bootstrap_file_for_site = MW_ROOTPATH . 'bootstrap_localhost' . '.php';
    } else {
        $sever = str_ireplace('www.', '', strtolower($_SERVER["SERVER_NAME"]));
        $sever = str_ireplace('..', '', ($_SERVER["SERVER_NAME"]));
        $config_file_for_site = MW_ROOTPATH . 'config_' . $sever . '.php';
        $bootstrap_file_for_site = MW_ROOTPATH . 'bootstrap_' . $sever . '.php';
    }
    if (is_file($config_file_for_site)) {
        define('MW_CONFIG_FILE', $config_file_for_site);

    } else {
        define('MW_CONFIG_FILE', MW_ROOTPATH . 'config.php');
    }
    if ($bootstrap_file_for_site != false and is_file($bootstrap_file_for_site)) {
        require_once($bootstrap_file_for_site);
    }
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
if (!defined('MW_ADAPTERS_DIR')) {
    define('MW_ADAPTERS_DIR', MW_APP_PATH . 'Adapters' . DS);
}


if (!defined('MW_WEB_COMPONENTS_FOLDER_NAME')) {
    define('MW_WEB_COMPONENTS_FOLDER_NAME', 'components');
}
if (!defined('MW_WEB_COMPONENTS_SHARED_FOLDER_NAME')) {
    define('MW_WEB_COMPONENTS_SHARED_FOLDER_NAME', 'components-shared');
}
if (!defined('MW_VENDOR_FOLDER_NAME')) {
    define('MW_VENDOR_FOLDER_NAME', 'vendor');
}
if (!defined('MW_VENDOR_SHARED_FOLDER_NAME')) {
    define('MW_VENDOR_SHARED_FOLDER_NAME', 'vendor-shared');
}
if (!defined('MW_WEB_COMPONENTS_URL')) {
    define('MW_WEB_COMPONENTS_URL', MW_SITE_URL . MW_WEB_COMPONENTS_FOLDER_NAME . '/');
}
if (!defined('MW_WEB_COMPONENTS_DIR')) {
    define('MW_WEB_COMPONENTS_DIR', MW_ROOTPATH . MW_WEB_COMPONENTS_FOLDER_NAME . DS);
}
$shared_web_components_dir_exists = false;
if (!defined('MW_WEB_COMPONENTS_SHARED_DIR')) {
    if (is_dir(MW_ROOTPATH . MW_WEB_COMPONENTS_SHARED_FOLDER_NAME)) {
        $shared_web_components_dir_exists = true;
        define('MW_WEB_COMPONENTS_SHARED_DIR', MW_ROOTPATH . MW_WEB_COMPONENTS_SHARED_FOLDER_NAME . DS);
    } else {
        $shared_web_components_dir_exists = false;
        define('MW_WEB_COMPONENTS_SHARED_DIR', MW_ROOTPATH . MW_WEB_COMPONENTS_FOLDER_NAME . DS);
    }
}


class Psr4AutoloaderClass
{
    /**
     * An associative array where the key is a namespace prefix and the value
     * is an array of base directories for classes in that namespace.
     *
     * @var array
     */
    protected $prefixes = array();

    /**
     * Register loader with SPL autoloader stack.
     *
     * @return void
     */
    public function register()
    {
        spl_autoload_register(array($this, 'loadClass'));
    }

    /**
     * Adds a base directory for a namespace prefix.
     *
     * @param string $prefix The namespace prefix.
     * @param string $base_dir A base directory for class files in the
     * namespace.
     * @param bool $prepend If true, prepend the base directory to the stack
     * instead of appending it; this causes it to be searched first rather
     * than last.
     * @return void
     */
    public function addNamespace($prefix, $base_dir, $prepend = false)
    {
        // normalize namespace prefix
        $prefix = trim($prefix, '\\') . '\\';

        if (is_array($base_dir)) {

            foreach ($base_dir as $item) {
                return $this->addNamespace($prefix, $item, $prepend);
            }

        } elseif (is_string($base_dir) and is_dir($base_dir)) {


            // normalize the base directory with a trailing separator
            $base_dir = rtrim($base_dir, '/') . DIRECTORY_SEPARATOR;
            $base_dir = rtrim($base_dir, DIRECTORY_SEPARATOR) . '/';

            // initialize the namespace prefix array
            if (isset($this->prefixes[$prefix]) === false) {
                $this->prefixes[$prefix] = array();
            }

            // retain the base directory for the namespace prefix

            set_include_path(get_include_path() . PATH_SEPARATOR . $base_dir);

            if ($prepend) {
                array_unshift($this->prefixes[$prefix], $base_dir);
            } else {
                array_push($this->prefixes[$prefix], $base_dir);
            }
        }
    }

    /**
     * Loads the class file for a given class name.
     *
     * @param string $class The fully-qualified class name.
     * @return mixed The mapped file name on success, or boolean false on
     * failure.
     */
    public function loadClass($class)
    {
        // the current namespace prefix
        $prefix = $class;

        // work backwards through the namespace names of the fully-qualified
        // class name to find a mapped file name
        while (false !== $pos = strrpos($prefix, '\\')) {

            // retain the trailing namespace separator in the prefix
            $prefix = substr($class, 0, $pos + 1);

            // the rest is the relative class name
            $relative_class = substr($class, $pos + 1);

            // try to load a mapped file for the prefix and relative class
            $mapped_file = $this->loadMappedFile($prefix, $relative_class);
            if ($mapped_file) {
                return $mapped_file;
            }

            // remove the trailing namespace separator for the next iteration
            // of strrpos()
            $prefix = rtrim($prefix, '\\');
        }
        // $mapped_file = $this->loadMappedFile($prefix, $relative_class);
        // never found a mapped file
        return false;
    }

    /**
     * Load the mapped file for a namespace prefix and relative class.
     *
     * @param string $prefix The namespace prefix.
     * @param string $relative_class The relative class name.
     * @return mixed Boolean false if no mapped file can be loaded, or the
     * name of the mapped file that was loaded.
     */
    protected function loadMappedFile($prefix, $relative_class)
    {

        // are there any base directories for this namespace prefix?
        if (isset($this->prefixes[$prefix]) === false) {
            return false;
        }

        // look through base directories for this namespace prefix
        foreach ($this->prefixes[$prefix] as $base_dir) {

            // replace the namespace prefix with the base directory,
            // replace namespace separators with directory separators
            // in the relative class name, append with .php
            $file = $base_dir
                . str_replace('\\', DIRECTORY_SEPARATOR, $relative_class)
                . '.php';
            $file = $base_dir
                . str_replace('\\', '/', $relative_class)
                . '.php';

            // if the mapped file exists, require it


            if ($this->requireFile($file)) {
                // yes, we're done
                return $file;
            }
        }

        // never found it
        return false;
    }

    /**
     * If a file exists, require it from the file system.
     *
     * @param string $file The file to require.
     * @return bool True if the file exists, false if not.
     */
    protected function requireFile($file)
    {
        if (file_exists($file)) {
            require $file;
            return true;
        }
        return false;
    }
}

$loader = new Psr4AutoloaderClass;
$mw_src = (__DIR__) . DS;

$loader->addNamespace('Microweber', $mw_src);
$loader->addNamespace('Microweber', MW_APP_PATH . 'controllers');


$autoload_vendors_dir = MW_ROOTPATH . MW_VENDOR_FOLDER_NAME . DIRECTORY_SEPARATOR;
$autoload_vendors_shared_dir = MW_ROOTPATH . MW_VENDOR_SHARED_FOLDER_NAME . DIRECTORY_SEPARATOR;
$autoload_vendors_file = $autoload_vendors_dir . 'autoload.php';
$autoload_vendors_composer_dir = $autoload_vendors_dir . 'composer' . DS;
$autoload_vendors_composer_autoload_real = $autoload_vendors_composer_dir . 'autoload_real.php';
$autoload_vendors_composer_autoload_files = $autoload_vendors_composer_dir . 'autoload_files.php';
$autoload_vendors_composer_autoload_namespaces = $autoload_vendors_composer_dir . 'autoload_namespaces.php';
$autoload_vendors_composer_autoload_include_paths = $autoload_vendors_composer_dir . 'include_paths.php';


$autoload_vendors_shared_file = $autoload_vendors_shared_dir . 'autoload.php';
if (is_file($autoload_vendors_shared_file)) {
    include_once($autoload_vendors_shared_file);
}
$use_fallback_autoloader_config = false;
if (is_file($autoload_vendors_file)) {
    include_once($autoload_vendors_file);
} elseif (is_dir($autoload_vendors_composer_dir) and is_file($autoload_vendors_composer_autoload_real) and is_file($autoload_vendors_composer_autoload_files)) {
    //setup for shared vendor folder env
    include_once($autoload_vendors_composer_autoload_real);
    $functions = get_defined_functions();
    $functions_list = array();
    foreach ($functions['user'] as $func) {
        $search_for_composer_require = 'composerRequire';
        if (stristr($func, $search_for_composer_require) !== false) {
            $composer_class = str_ireplace($search_for_composer_require, '', $func);
            $composer_class = 'ComposerAutoloaderInit' . $composer_class;
            if (class_exists($composer_class)) {
                $required = include($autoload_vendors_composer_autoload_files);
                //$composer_class::getLoader();
            }
        }

    }
} elseif (is_dir($autoload_vendors_composer_dir) and is_file($autoload_vendors_composer_autoload_namespaces) and is_file($autoload_vendors_composer_autoload_include_paths)) {
    //setup for shared vendor folder env
    $required = include($autoload_vendors_composer_autoload_namespaces);
    if (is_array($required) and !empty($required)) {
        foreach ($required as $k => $v) {

            $loader->addNamespace($k, $v, true);


        }

    }

}


if (!defined('mw_includes_url()')) {
    define('mw_includes_url()', mw_path_to_url(MW_INCLUDES_DIR));
}
if (!defined('INCLUDES_URL')) {
    define('INCLUDES_URL', mw_includes_url());
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
    if (defined('MW_CONFIG_FILE')) {
        $mw_cache_subfolder = 'mw_cache' . crc32(MW_CONFIG_FILE);
    }

    define('MW_CACHE_DIR', MW_CACHE_ROOT_DIR . $mw_cache_subfolder . DS);
}
if (!defined('MW_USERFILES')) {
    define('MW_USERFILES', MW_ROOTPATH . MW_USERFILES_FOLDER_NAME . DS);
}
if (!defined('MW_USERFILES_URL')) {

    $userfiles_url = str_replace(MW_ROOTPATH, '', MW_USERFILES);
    $userfiles_url = str_replace('\\', '/', $userfiles_url);
    $userfiles_url = str_replace('//', '/', $userfiles_url);
    define("MW_USERFILES_URL", MW_SITE_URL . $userfiles_url);
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
if (!defined('MW_TEMPLATES_URL')) {
    define('MW_TEMPLATES_URL', MW_USERFILES_URL . '/' . MW_TEMPLATES_FOLDER_NAME . '/');
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
if (!defined('MODULES_URL')) {
    define('MODULES_URL', MW_MODULES_URL);
}
if (!defined('MW_USER_IP')) {
    if (isset($_SERVER["REMOTE_ADDR"])) {
        define("MW_USER_IP", $_SERVER["REMOTE_ADDR"]);
    } else {
        define("MW_USER_IP", '127.0.0.1');
    }
}


if (!defined('MW_STORAGE_DIR')) {
    define('MW_STORAGE_DIR', MW_USERFILES . 'storage' . DS);
}
if (!defined('T')) {
    $mtime = microtime();
    $mtime = explode(" ", $mtime);
    $mtime = $mtime[1] + $mtime[0];
    define('T', $mtime);
}

// magic quotes fix
// http://php.net/manual/en/function.get-magic-quotes-gpc.php
// http://stackoverflow.com/questions/3117512/prevent-automatic-add-slashes-while-using-parse-str
if (function_exists('get_magic_quotes_runtime') and function_exists('set_magic_quotes_runtime') and get_magic_quotes_runtime()) {
    @set_magic_quotes_runtime(0);
    @set_magic_quotes_runtime(0);
}
function stripslashes_magic_quotes_gpc($array)
{
    foreach ($array as $key => $value) {
        $array[$key] = is_array($value) ?
            stripslashes_magic_quotes_gpc($value) :
            stripslashes($value);
    }
    return $array;
}

if (function_exists('get_magic_quotes_gpc') and get_magic_quotes_gpc()) {
    $_GET = stripslashes_magic_quotes_gpc($_GET);
    $_POST = stripslashes_magic_quotes_gpc($_POST);
    $_COOKIE = stripslashes_magic_quotes_gpc($_COOKIE);
    $_REQUEST = stripslashes_magic_quotes_gpc($_REQUEST);
}


//$loader->addNamespace('\\', MW_APP_PATH . 'libs');
$loader->addNamespace('Microweber', MW_MODULES_DIR);

$loader->register();


/**
 * Constructor function
 *
 * @param null $class
 * @param bool $constructor_params
 * @return \Microweber\Application Microweber Application object
 */
function removemw($class = null, $constructor_params = false)
{

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

/**
 *  Application Facade class
 *
 * Class that returns instance of the Application class
 *
 * @package Application
 * @property \Microweber\Adapters $adapters
 * @property \Microweber\Url $url
 * @property \Microweber\Content $content
 * @property \Microweber\Category $category
 * @property \Microweber\Media $media
 * @property \Microweber\Shop $shop
 * @property \Microweber\Option $option
 * @property \Microweber\Cache $cache
 * @property \Microweber\User $user
 * @property \Microweber\Module $module
 * @property \Microweber\Db $db
 * @property \Microweber\Notifications $notifications
 * @property \Microweber\Layouts $layouts
 * @property \Microweber\Log $log
 * @property \Microweber\Parser $parser
 * @property \Microweber\Format $format
 * @property \Microweber\Http $http
 * @property \Microweber\Template $template
 * @property \Microweber\Ui $ui
 */
class mw
{

    /**
     * Handle static calls to the object.
     *
     * // got this from laravel - https://github.com/illuminate/support/blob/master/Facades/Facade.php
     * @param  string $method
     * @param  array $args
     * @return mixed
     * @return \Microweber\Application
     * @property \Microweber\Application $application
     */
    public static function __callStatic($method, $args)
    {
        $instance = mw();

        switch (count($args)) {
            case 0:
                return $instance->$method();

            case 1:
                return $instance->$method($args[0]);

            case 2:
                return $instance->$method($args[0], $args[1]);

            case 3:
                return $instance->$method($args[0], $args[1], $args[2]);

            case 4:
                return $instance->$method($args[0], $args[1], $args[2], $args[3]);

            default:
                return call_user_func_array(array($instance, $method), $args);
        }
    }

}

/*
* Microweber autoloader
* Loads up classes with namespaces
* Add more directories with set_include_path
*/

set_include_path(dirname(MW_APP_PATH) . PATH_SEPARATOR .
    MW_APP_PATH . PATH_SEPARATOR .

    PATH_SEPARATOR . modules_path() .
    //PATH_SEPARATOR . $libs_path .
    PATH_SEPARATOR . $autoload_vendors_shared_dir .
    PATH_SEPARATOR . get_include_path());

spl_autoload_register('mw_autoload');


// Basic system functions

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
        $fileName .= str_replace('__', DIRECTORY_SEPARATOR, $className) . '.php';
        // if(is_file($fileName)){
        include_once($fileName);
        // }
    }

}

function autoload_add($dirname)
{
    set_include_path($dirname .
        PATH_SEPARATOR . get_include_path());
}


$_mw_config_file_values = array();
function _reload_c($new_config = false)
{
    global $_mw_config_file_values;

    if (defined('MW_CONFIG_FILE') and MW_CONFIG_FILE != false and is_file(MW_CONFIG_FILE)) {

        include(MW_CONFIG_FILE);
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

            include_once(MW_CONFIG_FILE);
            if (isset($config)) {
                $_mw_config_file_values = $config;
                if (isset($_mw_config_file_values[$k])) {

                    return $_mw_config_file_values[$k];
                }
            } else {
                include(MW_CONFIG_FILE);
                if (isset($config)) {
                    $_mw_config_file_values = $config;
                    if (isset($_mw_config_file_values[$k])) {

                        return $_mw_config_file_values[$k];
                    }
                }
            }
        }


    }
}

function d($v)
{

    $wrap = " \n\n\ ";
    $ret = $wrap . '<pre>' . var_dump($v) . '</pre>' . $wrap;

    return $ret;
    //return dump($v);
}

function mwdbg($q)
{

    static $mwdbg;
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
    include_once(MW_APP_PATH . 'functions' . DIRECTORY_SEPARATOR . 'language.php');

    $v = new \Weber\View(MW_ADMIN_VIEWS_DIR . 'error.php');
    $v->e = $e;
    $v->f = $f;
    $v->l = $l;
    die($v);
}


function site_url($add_string = false)
{
    static $site_url;

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

        if (isset($_SERVER['QUERY_STRING'])) {
            $pageURL = str_replace($_SERVER['QUERY_STRING'], '', $pageURL);
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
    $path = str_ireplace(MW_ROOTPATH, '', $path);
    $path = str_replace('\\', '/', $path);
    $path = str_replace('//', '/', $path);
    $path = str_ireplace(MW_ROOTPATH, '', $path);
    $this_file = @dirname(dirname(dirname(__FILE__)));
    $path = str_ireplace($this_file, '', $path);
    $path = str_replace('\\', '/', $path);
    $path = str_replace('//', '/', $path);

    $path = ltrim($path, '/');
    $path = ltrim($path, '\\');
    return site_url($path);
}


require_once(MW_APP_PATH . 'functions' . DS . 'mw_functions.php');

