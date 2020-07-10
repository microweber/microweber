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

namespace MicroweberPackages\Content\MenuManager;

use Illuminate\Support\ServiceProvider;

class MenuManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * @property \MicroweberPackages\Content\MenuManager\MenuManager    $menu_manager
         */
        $this->app->singleton('menu_manager', function ($app) {
            return new MenuManager();
        });
    }
}