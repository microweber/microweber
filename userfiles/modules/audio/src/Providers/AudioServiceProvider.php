<?php

namespace MicroweberPackages\Modules\Audio\Providers;

use Filament\Facades\Filament;
use Livewire\Livewire;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Modules\Audio\Filament\AudioModuleSettings;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MicroweberPackages\Modules\Audio\Http\Livewire\AudioSettingsComponent;

class AudioServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-audio');
        $package->hasViews('microweber-module-audio');
    }

    public function register(): void
    {
        parent::register();
        FilamentRegistry::registerPage(AudioModuleSettings::class);
    }

    public function boot(): void
    {
        Filament::serving(function () {
            $panelId = Filament::getCurrentPanel()->getId();
            if ($panelId == 'admin') {
                ModuleAdmin::registerLiveEditSettingsUrl('audio', AudioModuleSettings::getUrl());
            }
        });

    }

}
