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

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use MicroweberPackages\Template\Http\Livewire\Admin\AdminTemplateUpdateModal;
use MicroweberPackages\Template\Http\Livewire\Admin\LiveEditTemplateSettingsSidebar;
use MicroweberPackages\Template\Repositories\TemplateMetaTagsRepository;

class TemplateManagerServiceProvider extends ServiceProvider
{

    public function boot()
    {
        Livewire::component('admin-template-update-modal', AdminTemplateUpdateModal::class);
        Livewire::component('live-edit-template-settings-sidebar', LiveEditTemplateSettingsSidebar::class);
    }

    public function register()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');


        /**
         * @property \MicroweberPackages\Template\Repositories\TemplateMetaTagsRepository    $template_meta_tags
         */
        $this->app->singleton('template_meta_tags', function ($app) {
            return new TemplateMetaTagsRepository();
        });

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


        View::addNamespace('template', __DIR__.'/resources/views');

    }
}
