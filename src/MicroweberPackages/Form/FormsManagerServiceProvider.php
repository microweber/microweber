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

namespace MicroweberPackages\Form;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Field\FieldsManager;


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
         * @property \MicroweberPackages\Form\FormsManager $forms_manager
         */
        $this->app->singleton('forms_manager', function ($app) {
            return new FormsManager();
        });

        /**
         * @property \MicroweberPackages\Field\FieldsManager $fields_manager
         */
        $this->app->singleton('fields_manager', function ($app) {
            return new FieldsManager();
        });

        $this->loadMigrationsFrom(__DIR__ . '/migrations/');
    }
}
