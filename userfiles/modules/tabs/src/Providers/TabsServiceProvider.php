<?php

namespace MicroweberPackages\Modules\Tabs\Providers;

use Livewire\Livewire;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MicroweberPackages\Modules\Tabs\Http\Livewire\TabsSettingsComponent;

class TabsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-tabs');
        $package->hasViews('microweber-module-tabs');
    }

    public function register(): void
    {
        parent::register();

//        Livewire::component('microweber-module-tabs::settings', TabsSettingsComponent::class);
//        ModuleAdmin::registerSettings('tabs', 'microweber-module-tabs::settings');
////
        ModuleAdmin::registerLiveEditSettingsUrl('tabs', site_url('admin-live-edit/tabs-module-settings'));
        ModuleAdmin::registerLiveEditPanelPage(\MicroweberPackages\Modules\Tabs\Http\Livewire\TabsModuleSettings::class);

    }

}
