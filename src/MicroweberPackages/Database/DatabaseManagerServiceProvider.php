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

namespace MicroweberPackages\Database;

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
         * @property \MicroweberPackages\Database\DatabaseManager    $database_manager
         */
        $this->app->singleton('database_manager', function ($app) {
            return new DatabaseManager($app);
        });


        \Event::listen(['eloquent.saved: *', 'eloquent.created: *', 'eloquent.deleted: *'], function ($context) {
            app()->database_manager->clearCache();
        });


    }
}
