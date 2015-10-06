<?php

if (version_compare(phpversion(), "5.3.0", "<=")) {
    exit("Error: You must have PHP version 5.3 or greater to run Microweber");
}
if (!defined('T')) {
    $mtime = microtime();
    $mtime = explode(" ", $mtime);
    $mtime = $mtime[1] + $mtime[0];
    define('T', $mtime);
}

if (!defined('MW_VERSION')) {
    define('MW_VERSION', '1.0.5');
}

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

if (!defined('MW_PATH')) {
    define('MW_PATH', realpath(__DIR__ . '/../') . DS);
}


if (!defined('MW_ROOTPATH')) {
    define('MW_ROOTPATH', base_path() . DS);
}

if (!defined('MW_USERFILES_FOLDER_NAME')) {
    define('MW_USERFILES_FOLDER_NAME', 'userfiles'); //relative to public dir
}
if (!defined('MW_MODULES_FOLDER_NAME')) {
    define('MW_MODULES_FOLDER_NAME', 'modules'); //relative to userfiles dir
}
if (!defined('MW_ELEMENTS_FOLDER_NAME')) {
    define('MW_ELEMENTS_FOLDER_NAME', 'elements'); //relative to userfiles dir
}
if (!defined('MW_MEDIA_FOLDER_NAME')) {
    define('MW_MEDIA_FOLDER_NAME', 'media'); //relative to userfiles dir
}


if (!defined('MW_TEMPLATES_FOLDER_NAME')) {
    define('MW_TEMPLATES_FOLDER_NAME', 'templates'); //relative to userfiles dir
}
if (!defined('MW_SYSTEM_MODULE_FOLDER')) {
    define('MW_SYSTEM_MODULE_FOLDER', 'microweber'); //relative to modules dir
}
if (!defined('MW_USER_IP')) {
    if (isset($_SERVER["REMOTE_ADDR"])) {
        define("MW_USER_IP", $_SERVER["REMOTE_ADDR"]);
    } else {
        define("MW_USER_IP", '127.0.0.1');
    }
}


$functions_dir = __DIR__ . DS;

include_once($functions_dir . 'paths.php');
 
include_once($functions_dir . 'api.php');
include_once($functions_dir . 'api_callbacks.php');
include_once($functions_dir . 'filesystem.php');
include_once($functions_dir . 'lang.php');
include_once($functions_dir . 'events.php');
include_once($functions_dir . 'config.php');
 
include_once($functions_dir . 'db.php');
include_once($functions_dir . 'user.php');
include_once($functions_dir . 'common.php');
include_once($functions_dir . 'media.php');
include_once($functions_dir . 'other.php');
include_once($functions_dir . 'content.php');
include_once($functions_dir . 'custom_fields.php');
include_once($functions_dir . 'menus.php');
include_once($functions_dir . 'categories.php');
include_once($functions_dir . 'options.php');
include_once($functions_dir . 'shop.php');
include_once($functions_dir . 'modules.php');

