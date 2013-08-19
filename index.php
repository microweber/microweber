<?php

date_default_timezone_set('UTC');
if (!defined('M')) {
define('M', memory_get_usage());
}


if (!defined('MW_USE_APC_CACHE')) {
define('MW_USE_APC_CACHE', false); //mw will automatically use apc if its found, but you can turn it off
}

if (!defined('MW_ROOTPATH')) {
    define('MW_ROOTPATH', dirname((__FILE__)) . DIRECTORY_SEPARATOR);
}


if (!isset($_SERVER["SERVER_NAME"])) {
    $config_file_for_site = MW_ROOTPATH . 'config_localhost' . '.php';
} else {
    $no_www = str_ireplace('www.', '', $_SERVER["SERVER_NAME"]);
    $config_file_for_site = MW_ROOTPATH . 'config_' . $no_www . '.php';
}

if (!defined('MW_CONFIG_FILE')) {
	if (is_file($config_file_for_site)) {
		define('MW_CONFIG_FILE', $config_file_for_site);
	
	} else {
		define('MW_CONFIG_FILE', MW_ROOTPATH . 'config.php');
		
	}

}


require_once (MW_ROOTPATH . 'src/Microweber/bootstrap.php');

error_reporting(E_ALL);


// Starting MW


$application = new \Microweber\Application(MW_CONFIG_FILE);


/*

  You can extend every function of MW try this
  $application = new \Microweber\MyApp(MW_CONFIG_FILE);


 After start you can use the methods of your application trough
 the mw() function, which returns the latest application instance

 $temp = mw()->content->get("is_active=y");
 var_dump($temp);

 $temp = mw()->media->get();
 var_dump($temp);

 $temp = $application->users->get();
 var_dump($temp);



*/


// Starting Router
$router = new \Microweber\Router();


// Starting Controller
$controller = new \Microweber\Controller($application);


// Automatically map the Router to all controller functions
$router->map($controller);


// Extend and override the Controller
$controller->heldlo_world = function () {
    echo "Hello world!";
};

// Map more complex routes with regex, the Router is using preg_match
$controller->functions['test/route/*'] = function () {
    echo "You can use wildcards!";
};



// Run the website
$router->run();


exit();



