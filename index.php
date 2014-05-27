<?php
if (version_compare(phpversion(), "5.3.0", "<=")) {
    exit("Error: You must have PHP version 5.3 or greater to run Microweber");
}
//error_reporting(E_ALL);
 
date_default_timezone_set('UTC');


require_once ('src/Microweber/bootstrap.php');

$application = new \Microweber\Application();

/*

  You can extend every function of MW try this
  $application = new \Microweber\MyApp();

 After creating new "Application" you can use the methods of your application trough
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
/*
$rou
ter->my_controller_url =  '\Microweber\TestController';

Add more controllers tp the router
open at http://localhost/my_controller_url

*/
// Run the website
$router->run();


 
