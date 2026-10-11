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
use MicroweberPackages\Template\Services\DesignSystem\ColorSchemesRegistry;
use MicroweberPackages\Template\Services\DesignSystem\DesignSystemService;
use MicroweberPackages\Template\Services\DesignSystem\StylePackRegistry;

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

        // Standalone template-fonts package (Google/custom fonts + template_fonts table).
        $this->app->register(\MicroweberPackages\TemplateFonts\TemplateFontsServiceProvider::class);

        // Standalone template-custom-css package (live_edit.css + user custom CSS).
        $this->app->register(\MicroweberPackages\TemplateCustomCss\TemplateCustomCssServiceProvider::class);

        // Standalone minifier package (JS/CSS) used by AssetOptimizationService.
        // Hard dependency (microweber-packages/minifier) — register directly so a
        // missing package fails loudly at boot instead of silently disabling minification.
        $this->app->register(\MicroweberPackages\Minifier\MinifierServiceProvider::class);

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

        /**
         * @property \MicroweberPackages\Template\Services\DesignSystem\ColorSchemesRegistry $color_schemes_registry
         */
        $this->app->singleton('color_schemes_registry', function ($app) {
            $registry = new ColorSchemesRegistry();
            $registry->loadSharedPalettes();
            return $registry;
        });

        /**
         * @property \MicroweberPackages\Template\Services\DesignSystem\StylePackRegistry $style_pack_registry
         */
        $this->app->singleton('style_pack_registry', function ($app) {
            $registry = new StylePackRegistry();
            $registry->loadSharedPacks();
            return $registry;
        });

        /**
         * @property \MicroweberPackages\Template\Services\DesignSystem\DesignSystemService $design_system
         */
        $this->app->singleton('design_system', function ($app) {
            return new DesignSystemService(
                $app->make('color_schemes_registry'),
                $app->make('style_pack_registry')
            );
        });

    }
}
