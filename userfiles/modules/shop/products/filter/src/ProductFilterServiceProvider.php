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

namespace MicroweberPackages\Shop\Products\Filter;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ProductFilterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        View::addNamespace('productFilter', (__DIR__) . '/resources/views');
    }
}
