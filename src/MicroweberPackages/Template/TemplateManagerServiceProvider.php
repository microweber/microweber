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

namespace MicroweberPackages\Template;

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
        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');


        /**
         * @property \MicroweberPackages\Template\TemplateManager    $template_manager
         */
        $this->app->singleton('template_manager', function ($app) {
            return new TemplateManager();
        });

        /**
         * @property \MicroweberPackages\Template\layoutsManager    $layouts_manager
         */
        $this->app->singleton('layouts_manager', function ($app) {
            return new LayoutsManager();
        });

        /**
         * @property \MicroweberPackages\Template\Template    $template
         */
        $this->app->singleton('template', function ($app) {
            return new Template();
        });
    }
}
