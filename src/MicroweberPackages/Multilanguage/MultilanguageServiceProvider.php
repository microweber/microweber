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

namespace MicroweberPackages\Multilanguage;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Form\FormElementBuilder;

class MultilanguageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        if (defined('MW_DISABLE_MULTILANGUAGE')) {
            return;
        }

        // Check multilanguage is active
        if (is_module('multilanguage') && get_option('is_active', 'multilanguage_settings') !== 'y') {
            return;
        }

        $this->loadMigrationsFrom(__DIR__ . '/migrations/');
    }

    public function register()
    {

        $this->app->bind('multilanguage_repository', function () {
            return new \MicroweberPackages\Multilanguage\Repositories\MultilanguageRepository();
        });

        if (defined('MW_DISABLE_MULTILANGUAGE')) {
            return;
        }

        // Check multilanguage is active
        if (is_module('multilanguage') && get_option('is_active', 'multilanguage_settings') !== 'y') {
            return;
        }

        $this->app->register(MultilanguageEventServiceProvider::class);

        $this->app->bind(FormElementBuilder::class, function ($app) {
            return new MultilanguageFormElementBuilder();
        });

    }
}
