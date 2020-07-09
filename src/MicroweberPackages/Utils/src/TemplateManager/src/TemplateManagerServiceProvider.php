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

namespace MicroweberPackages\Utils\TemplateManager;

use Illuminate\Support\ServiceProvider;


class TemplateManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * @property \MicroweberPackages\Utils\TemplateManager\TemplateManager    $template_manager
         */
        $this->app->singleton('template_manager', function ($app) {
            return new TemplateManager();
        });

        /**
         * @property \MicroweberPackages\Utils\TemplateManager\layoutsManager    $layouts_manager
         */
        $this->app->singleton('layouts_manager', function ($app) {
            return new LayoutsManager();
        });

        /**
         * @property \MicroweberPackages\Utils\TemplateManager\Template    $template
         */
        $this->app->singleton('template', function ($app) {
            return new Template();
        });
    }
}
