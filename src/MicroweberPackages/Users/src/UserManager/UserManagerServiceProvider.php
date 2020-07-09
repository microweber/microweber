<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber LTD
 *
 * For full license information see
 * http://Microweber.com/license/
 *
 */

namespace MicroweberPackages\UserManager;

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
         * @property \MicroweberPackages\UserManager\UserManager    $user_manager
         */
        $this->app->singleton('user_manager', function ($app) {
            return new UserManager();
        });

    }
}
