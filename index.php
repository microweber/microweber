<?php

date_default_timezone_set('UTC');
define('M', memory_get_usage());
  define('MW_USE_APC_CACHE', false);
if (!defined('MW_ROOTPATH')) {
    define('MW_ROOTPATH', dirname((__FILE__)) . DIRECTORY_SEPARATOR);
}

error_reporting(E_ALL);
if (!isset($_SERVER["SERVER_NAME"])) {
    $config_file_for_site = MW_ROOTPATH . 'config_localhost' . '.php';
} else {
    $no_www = str_ireplace('www.', '', $_SERVER["SERVER_NAME"]);
    $config_file_for_site = MW_ROOTPATH . 'config_' . $no_www . '.php';
}
if (is_file($config_file_for_site)) {
    define('MW_CONFIG_FILE', $config_file_for_site);

} else {
    define('MW_CONFIG_FILE', MW_ROOTPATH . 'config.php');
    $config_file_for_site = MW_ROOTPATH . 'config.php';
}

require_once (MW_ROOTPATH . 'src/Microweber/bootstrap.php');


 //$application = new \Microweber\Application(MW_CONFIG_FILE);
  //$application = new \Microweber\MyApp(MW_CONFIG_FILE);

// or
 $application = mw('application',MW_CONFIG_FILE);

$installed = $application->config('installed');

if (strval($installed) != 'yes') {
    define('MW_IS_INSTALLED', false);
} else {
    define('MW_IS_INSTALLED', true);
}


$router = new \Microweber\Router();

$controller = new\Microweber\Controller($application);

$router->map($controller);
$router->hello_world = function () {
    echo "Hello world!";
};
//$temp = $application->media->thumbnail(2);
//d($temp);
//$temp = $application->content->get_layout($page_non_active);

$controller->functions['test/route/*'] = function () {
    echo "You can use wildcards!";
};
$controller->functions['test/api/user_login*'] = function () {
    echo "My user_login";
};

if (MW_IS_INSTALLED != true) {
    $controller->install();
    exit();
}
$outp =$router->run();


exit();



