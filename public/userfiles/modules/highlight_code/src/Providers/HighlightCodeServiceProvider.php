<?php

namespace MicroweberPackages\Modules\HighlightCode\Providers;

use Filament\Facades\Filament;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Modules\HighlightCode\Filament\HighlightCodeModuleSettings;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class HighlightCodeServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-highlight-code');
     }

    public function register(): void
    {
        parent::register();
        FilamentRegistry::registerPage(HighlightCodeModuleSettings::class);

    }

    public function boot(): void
    {
        parent::boot();
        Filament::serving(function () {
            $panelId = Filament::getCurrentPanel()->getId();
            if ($panelId == 'admin') {
                ModuleAdmin::registerLiveEditSettingsUrl('highlight_code', HighlightCodeModuleSettings::getUrl());
            }
        });

    }
}
