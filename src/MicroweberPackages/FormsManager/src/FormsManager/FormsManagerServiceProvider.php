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

namespace MicroweberPackages\FormsManager;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\FieldsManager\FieldsManager;


class FormsManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * @property \MicroweberPackages\FormsManager\FormsManager $forms_manager
         */
        $this->app->singleton('forms_manager', function ($app) {
            return new FormsManager();
        });

        /**
         * @property \MicroweberPackages\FieldsManager\FieldsManager $fields_manager
         */
        $this->app->singleton('fields_manager', function ($app) {
            return new FieldsManager();
        });

        $this->loadMigrationsFrom(dirname(dirname(__DIR__)) . '/migrations/');
    }
}
