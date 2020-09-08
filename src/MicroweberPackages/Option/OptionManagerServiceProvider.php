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

namespace MicroweberPackages\Option;

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
         * @property \MicroweberPackages\Option\OptionManager    $option_manager
         */
        $this->app->singleton('option_manager', function ($app) {
            return new OptionManager();
        });

        $this->loadMigrationsFrom(__DIR__ . '/migrations/');
    }
}
