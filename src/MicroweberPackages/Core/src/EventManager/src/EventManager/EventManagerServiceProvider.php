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

namespace MicroweberPackages\Core\EventManager;

use Illuminate\Support\ServiceProvider;


class EventManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * @property \MicroweberPackages\Core\EventManager\Event    $event_manager
         */
        $this->app->singleton('event_manager', function ($app) {
            return new Event($app);
        });
    }
}
