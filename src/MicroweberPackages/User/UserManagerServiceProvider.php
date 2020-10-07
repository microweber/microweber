<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\User;

use Illuminate\Auth\AuthServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use \Laravel\Sanctum\SanctumServiceProvider;

class UserManagerServiceProvider extends AuthServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
//    public function register()
//    {
//        parent::register();
//    }

    public function boot()
    {
        /**
         * @property \MicroweberPackages\User\UserManager $user_manager
         */
        $this->app->register(\Laravel\Sanctum\SanctumServiceProvider::class);

        $this->app->singleton('user_manager', function ($app) {
            return new UserManager();
        });


        View::addNamespace('user', __DIR__ . '/resources/views');

        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/migrations/');


        //  Passport::routes(); // Add this
        // Passport::enableImplicitGrant();

    }
}
