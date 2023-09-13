<?php

namespace MicroweberPackages\Modules\LayoutContent\Providers;

use Livewire\Livewire;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Modules\LayoutContent\Http\Livewire\LayoutContentDefaultSettingsTemplateComponent;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MicroweberPackages\Modules\LayoutContent\Http\Livewire\LayoutContentSettingsComponent;

class LayoutContentServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-layout-content');
        $package->hasViews('microweber-module-layout-content');
    }

    public function register(): void
    {
        parent::register();
        ModuleAdmin::registerSettingsComponent('layout_content', LayoutContentSettingsComponent::class);
        ModuleAdmin::registerSkinSettingsComponent('layout_content', 'default', LayoutContentDefaultSettingsTemplateComponent::class);
        //  Livewire::component('microweber-module-layout-content::settings', LayoutContentSettingsComponent::class);
        //    Livewire::component('microweber-module-layout_content::template-settings-default', LayoutContentDefaultSettingsTemplateComponent::class);

    }

}
