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

namespace MicroweberPackages\Invoice;

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
         * @property \MicroweberPackages\Invoice    $invoices_manager
         */
        $this->app->singleton('invoices_manager', function ($app) {
            return new InvoicesManager();
        });
    }
}