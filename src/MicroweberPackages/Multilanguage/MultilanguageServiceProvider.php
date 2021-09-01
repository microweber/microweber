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
use MicroweberPackages\Multilanguage\Repositories\MultilanguageRepository;

class MultilanguageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/migrations/');
    }

    public function register()
    {
        $isMultilanguageActive = false;
        if (is_module('multilanguage') && get_option('is_active', 'multilanguage_settings') == 'y') {
            $isMultilanguageActive = true;
        }

        if (defined('MW_DISABLE_MULTILANGUAGE')) {
            $isMultilanguageActive = false;
        }

        $this->app->bind('multilanguage_repository', function () {
            return new MultilanguageRepository();
        });

        if ($isMultilanguageActive) {
            $this->app->register(MultilanguageEventServiceProvider::class);
            $this->app->bind(FormElementBuilder::class, function ($app) {
                return new MultilanguageFormElementBuilder();
            });
        }
    }
}
