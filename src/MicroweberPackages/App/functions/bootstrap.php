<?php

if (version_compare(phpversion(), '7.3.0', '<=')) {
    exit('Error: You must have PHP version 7.3.0 or greater to run Microweber');
}
if (!defined('T')) {
    $mtime = microtime();
    $mtime = explode(' ', $mtime);
    $mtime = $mtime[1] + $mtime[0];
    define('T', $mtime);
}

if (!defined('MW_VERSION')) {
    //remember to change also in version.txt
    define('MW_VERSION', \MicroweberPackages\App\LaravelApplication::APP_VERSION);
}


if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

if (!defined('MW_PATH')) {
    define('MW_PATH', realpath(__DIR__.'/../').DS);
}

if (!defined('MW_ROOTPATH')) {
    define('MW_ROOTPATH', base_path().DS);
}

if (!defined('MW_USERFILES_FOLDER_NAME')) {
//    if (defined('MW_BOOT_FROM_PUBLIC_FOLDER')) {
//        define('MW_USERFILES_FOLDER_NAME', ''); //relative to public dir
//    } else {
//
//    }

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


$functions_dir = __DIR__.DS;

include_once $functions_dir.'paths.php';

include_once $functions_dir.'api.php';
include_once $functions_dir.'api_callbacks.php';
include_once $functions_dir.'lang.php';
include_once $functions_dir.'common.php';
include_once $functions_dir.'other.php';
include_once $functions_dir.'mail.php';

$functions_dir2 = dirname(MW_PATH).DS.'Helper'.DS.'functions'.DS;
include_once $functions_dir2.'array.php';
include_once $functions_dir2.'filesystem.php';
include_once $functions_dir2.'laravel.php';
include_once $functions_dir2.'string.php';
include_once $functions_dir2.'system.php';
include_once $functions_dir2.'url.php';
