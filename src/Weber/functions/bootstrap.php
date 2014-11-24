<?php

if (version_compare(phpversion(), "5.3.0", "<=")) {
    exit("Error: You must have PHP version 5.3 or greater to run Weber");
}


if (!defined('WB_VERSION')) {
    define('WB_VERSION', 0.96);
}

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

if (!defined('WB_PATH')) {
    define('WB_PATH', realpath(__DIR__ . '/../') . DS);
}

if (!defined('WB_ROOTPATH')) {
    define('WB_ROOTPATH', dirname(dirname(dirname(WB_PATH))) . DS);
}

if (!defined('WB_USERFILES_FOLDER_NAME')) {
    define('WB_USERFILES_FOLDER_NAME', 'userfiles'); //relative to public dir
}
if (!defined('WB_MODULES_FOLDER_NAME')) {
    define('WB_MODULES_FOLDER_NAME', 'modules'); //relative to userfiles dir
}
if (!defined('WB_ELEMENTS_FOLDER_NAME')) {
    define('WB_ELEMENTS_FOLDER_NAME', 'elements'); //relative to userfiles dir
}

if (!defined('WB_TEMPLATES_FOLDER_NAME')) {
    define('WB_TEMPLATES_FOLDER_NAME', 'templates'); //relative to userfiles dir
}
if (!defined('WB_SYSTEM_MODULE_FOLDER')) {
    define('WB_SYSTEM_MODULE_FOLDER', 'weber'); //relative to modules dir
}
if (!defined('WB_USER_IP')) {
    if (isset($_SERVER["REMOTE_ADDR"])) {
        define("WB_USER_IP", $_SERVER["REMOTE_ADDR"]);
    } else {
        define("WB_USER_IP", '127.0.0.1');
    }
}

include_once(__DIR__ . DS . 'paths.php');
include_once(__DIR__ . DS . 'filesystem.php');
include_once(__DIR__ . DS . 'lang.php');
include_once(__DIR__ . DS . 'events.php');
include_once(__DIR__ . DS . 'api.php');
include_once(__DIR__ . DS . 'common.php');
include_once(__DIR__ . DS . 'content.php');
include_once(__DIR__ . DS . 'modules.php');

