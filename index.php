<?php

date_default_timezone_set('UTC');
define('M', memory_get_usage());
//define('MW_USE_APC_CACHE', false);
if (!defined('MW_ROOTPATH')) {
    define('MW_ROOTPATH', dirname((__FILE__)) . DIRECTORY_SEPARATOR);
}


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


$mw = new \Microweber\Application(MW_CONFIG_FILE);
// or
//$mw = mw('app');


$installed = $mw->c('installed');

if (strval($installed) != 'yes') {
    define('MW_IS_INSTALLED', false);
} else {
    define('MW_IS_INSTALLED', true);
}


$router = new \Microweber\Router();

$controller = new\Microweber\Controller($mw);

$router->map($controller);
$router->hello_world = function () {
    echo "Hello world!";
};


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
$router->run();

exit();



