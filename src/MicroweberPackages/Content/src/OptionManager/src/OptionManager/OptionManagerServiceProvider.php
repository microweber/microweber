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

namespace MicroweberPackages\Content\OptionManager;

use Illuminate\Support\ServiceProvider;


class OptionManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * @property \MicroweberPackages\Content\OptionManager\OptionManager    $option_manager
         */
        $this->app->singleton('option_manager', function ($app) {
            return new OptionManager();
        });

        $this->loadMigrationsFrom(dirname(dirname(__DIR__)) . '/migrations/');
    }
}
