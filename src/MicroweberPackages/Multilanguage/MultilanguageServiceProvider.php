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
include_once (__DIR__.'/helpers/multilanguage_functions.php');

class MultilanguageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {




        $isMultilanguageActive = multilanguage_is_enabled();

        $this->app->bind('multilanguage_repository', function () {
            return new MultilanguageRepository();
        });

        if ($isMultilanguageActive) {
            // $this->app->register(MultilanguageEventServiceProvider::class);
            $this->app->bind(FormElementBuilder::class, function ($app) {
                return new MultilanguageFormElementBuilder();
            });
        }

        $this->loadMigrationsFrom(__DIR__ . '/migrations/');
    }

}
