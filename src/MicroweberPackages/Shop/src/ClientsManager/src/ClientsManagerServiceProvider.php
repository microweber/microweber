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

namespace MicroweberPackages\Shop\ClientsManager;

use Illuminate\Support\ServiceProvider;

class ClientsManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * @property \MicroweberPackages\Shop\ClientsManager    $clients_manager
         */
        $this->app->singleton('clients_manager', function ($app) {
            return new ClientsManager();
        });
    }
}