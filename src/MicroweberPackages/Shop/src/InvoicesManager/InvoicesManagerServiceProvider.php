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

namespace MicroweberPackages\InvoicesManager;

use Illuminate\Support\ServiceProvider;

class InvoicesManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * @property \MicroweberPackages\InvoicesManager\InvoicesManager    $invoices_manager
         */
        $this->app->singleton('invoices_manager', function ($app) {
            return new InvoicesManager();
        });
    }
}