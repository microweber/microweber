<?php

namespace MicroweberPackages\Modules\Tabs\Providers;

use Filament\Facades\Filament;
use Livewire\Livewire;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use \MicroweberPackages\Modules\Tabs\Filament\Admin\TabsModuleSettings;

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
        FilamentRegistry::registerPage(TabsModuleSettings::class);
    }

    public function boot(): void
    {
        parent::boot();
        Filament::serving(function () {
            $panelId = Filament::getCurrentPanel()->getId();
            if ($panelId == 'admin') {
                ModuleAdmin::registerLiveEditSettingsUrl('tabs', TabsModuleSettings::getUrl());
            }
        });
    }

}
