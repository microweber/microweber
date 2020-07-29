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

use Illuminate\Support\ServiceProvider;


class UserManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * @property \MicroweberPackages\User\UserManager    $user_manager
         */

        $this->app->singleton('user_manager', function ($app) {
            return new UserManager();
        });

        $this->loadMigrationsFrom(__DIR__ . '/migrations/');

    }
}
