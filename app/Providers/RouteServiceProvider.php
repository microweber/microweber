<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{


    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
      //  $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');

    }

    public function register()
    {
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');

    }

//    /**
//     * Configure the rate limiters for the application.
//     *
//     * @return void
//     */
//    protected function configureRateLimiting()
//    {
//        RateLimiter::for('api', function (Request $request) {
//            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
//        });
//    }
}
