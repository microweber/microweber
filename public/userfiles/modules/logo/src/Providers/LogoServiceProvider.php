<?php

namespace MicroweberPackages\Modules\Logo\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Modules\Logo\Http\Livewire\LogoModuleSettings;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;


class LogoServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-logo');
        $package->hasViews('microweber-module-logo');
    }


    public function register(): void
    {
        parent::register();

//        Livewire::component('microweber-module-logo::settings', LogoSettingsComponent::class);
//        ModuleAdmin::registerSettings('logo', 'microweber-module-logo::settings');
        //    ModuleAdmin::registerFilamentPage(\MicroweberPackages\Modules\Logo\Http\Livewire\LogoSettings::class);

        FilamentRegistry::registerPage(\MicroweberPackages\Modules\Logo\Http\Livewire\LogoModuleSettings::class);

      //  ModuleAdmin::registerLiveEditSettingsUrl('logo', site_url('admin-live-edit/logo-module-settings'));


    }

    public function boot(): void
    {
        parent::boot();
        Filament::serving(function () {
            $panelId = Filament::getCurrentPanel()->getId();
            if ($panelId == 'admin') {
              // ModuleAdmin::registerLiveEditSettingsUrl('logo', LogoModuleSettings::getUrl());
              ModuleAdmin::registerSettingsComponent('logo', LogoModuleSettings::class);
            }
        });

    }
}
