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

namespace MicroweberPackages\DatabaseManager;

use Illuminate\Support\ServiceProvider;


class DatabaseManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * @property \MicroweberPackages\DatabaseManager\DatabaseManager    $database_manager
         */
        $this->app->singleton('database_manager', function ($app) {
            return new DatabaseManager($app);
        });
    }
}
