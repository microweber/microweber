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

namespace MicroweberPackages\CategoryManager;

use Illuminate\Support\ServiceProvider;


class CategoryManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * @property \MicroweberPackages\CategoryManager\Category    $category_manager
         */
        $this->app->singleton('category_manager', function ($app) {
            return new CategoryManager();
        });
    }
}
