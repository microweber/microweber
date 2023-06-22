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

namespace MicroweberPackages\FormBuilder\Providers;

use Illuminate\Container\Container;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;
use MicroweberPackages\FormBuilder\FormElementBuilder;

class FormBuilderServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        View::addNamespace('form', dirname(__DIR__) . '/resources/views');
//
//        $this->app->bind(FormElementBuilder::class, function ($app) {
//            $container = $app->make(Container::class);
//            return new FormElementBuilder($container);
//        });
    }

}
