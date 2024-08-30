<?php

namespace MicroweberPackages\Modules\LayoutContent\Providers;

use Livewire\Livewire;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Modules\LayoutContent\Http\Livewire\LayoutContentDefaultSettingsTemplateComponent;
use MicroweberPackages\Modules\LayoutContent\Http\Livewire\LayoutContentModuleSettings;
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
//        Livewire::component('microweber-module-layout-content::settings', LayoutContentSettingsComponent::class);
//        Livewire::component('microweber-module-layout-content::template-settings-default', LayoutContentDefaultSettingsTemplateComponent::class);
//
//        ModuleAdmin::registerSettings('layout_content', 'microweber-module-layout-content::settings');
//        ModuleAdmin::registerSkinSettings('layout_content', 'default', 'microweber-module-layout-content::template-settings-default');



        FilamentRegistry::registerPage(LayoutContentModuleSettings::class);
        ModuleAdmin::registerLiveEditSettingsUrl('layout_content', site_url('admin-live-edit/layout-content-module-settings'));

    }

}
