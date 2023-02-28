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

namespace MicroweberPackages\Event;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;


class EventManagerServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {


        include_once __DIR__ . '/helpers.php';

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() : void
    {
        /**
         * @property \MicroweberPackages\Event\\EventManager    $event_manager
         */
        $this->app->singleton('event_manager', function ($app) {
            return new Event();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['event_manager'];
    }
}
