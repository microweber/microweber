<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application;

/*
|--------------------------------------------------------------------------
| Detect The Application Environment
|--------------------------------------------------------------------------
|
| MULTISITE MOTHERFUCKER!!!
|
*/

$env = $app->detectEnvironment(function(){
	
	$envName = null;

	/*$domains = array(
		'hai' => ['hui\.com'],
		'hoi' => ['hui\.net'],
	);*/

	$hostname = $_SERVER['HTTP_HOST'];

	if(isset($domains))
	if(count($domains))
	{
		foreach ($domains as $env => $match) {
			if(is_string($match) && $match == $hostname) {
				$envName = $env;
				break;
			}
			foreach ($match as $regex) {
				if(preg_match("/$regex/", $hostname)) {
					$envName = $env;
					break 2;
				}
			}
		}
	}
	
	if(isset($fallback)) {
		if($fallback)
			$envName = $fallback;
	}
	else {
		$envName = $hostname;
	}
	
	$paths = include 'paths.php';
	if(!file_exists($paths['app'] .'/config/'. $envName))
		$envName = 'production';

	return $envName;
});

/*
|--------------------------------------------------------------------------
| Bind Paths
|--------------------------------------------------------------------------
|
| Here we are binding the paths configured in paths.php to the app. You
| should not be changing these here. If you need to change these you
| may do so within the paths.php file and they will be bound here.
|
*/

$app->bindInstallPaths(require __DIR__.'/paths.php');

/*
|--------------------------------------------------------------------------
| Load The Application
|--------------------------------------------------------------------------
|
| Here we will load this Illuminate application. We will keep this in a
| separate location so we can isolate the creation of an application
| from the actual running of the application with a given request.
|
*/

$framework = $app['path.base'].
                 '/vendor/laravel/framework/src';

require $framework.'/Illuminate/Foundation/start.php';

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
