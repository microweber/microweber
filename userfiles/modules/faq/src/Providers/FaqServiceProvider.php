<?php

namespace MicroweberPackages\Modules\Faq\Providers;

use Filament\Events\ServingFilament;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Event;
use Livewire\Livewire;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Modules\Faq\Filament\FaqModuleSettings;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MicroweberPackages\Modules\Faq\Http\Livewire\FaqSettingsComponent;

class FaqServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-faq');
        $package->hasViews('microweber-module-faq');
    }

    public function register(): void
    {
        parent::register();

        FilamentRegistry::registerPage(FaqModuleSettings::class);


    }

    public function boot(): void
    {
        parent::boot();
        Filament::serving(function () {
            $panelId = Filament::getCurrentPanel()->getId();
            if ($panelId == 'admin') {
             //   ModuleAdmin::registerLiveEditSettingsUrl('faq', FaqModuleSettings::getUrl());
                ModuleAdmin::registerSettingsComponent('faq', FaqModuleSettings::class);
            }
        });
    }

}
