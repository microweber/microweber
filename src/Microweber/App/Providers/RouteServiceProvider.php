<?php namespace Microweber\App\Providers;

use Illuminate\Routing\Router;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use \Route;

class RouteServiceProvider extends ServiceProvider {

	/**
	 * This namespace is applied to the controller routes in your routes file.
	 *
	 * In addition, it is set as the URL generator's root namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'Microweber\App\Http\Controllers';

	/**
	 * Define your route model bindings, pattern filters, etc.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
    public function boot()
    {
        \Route::pattern('domain', '[a-z0-9.\-]+');
        parent::boot();
    }

	/**
	 * Define the routes for the application.
	 *
	 * @return void
	 */
	public function map()
	{
		//$this->loadRoutesFrom(app_path('Http/routes.php'));
	}

}
