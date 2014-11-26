<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function index()
	{
        $connection =  Config::get('database.connections');
        // var_dump($connection);


        $connection =  Config::set('microweber.is_installed', 1);
        var_dump($connection);


        $connection =  Config::get('microweber.is_installed');
        var_dump($connection);

        $connection =  Config::save();
        var_dump($connection);
        exit;
	}

}
