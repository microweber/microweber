<?php

namespace MicroweberPackages\Modules\Editor\ResetContent\Providers;

use Filament\Facades\Filament;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Modules\Editor\ResetContent\Filament\ResetContentModuleSettingsPage;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ResetContentModuleServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-reset-content');
        $package->hasViews('microweber-module-reset-content');
    }

    public function register(): void
    {

        parent::register();

        FilamentRegistry::registerPage(ResetContentModuleSettingsPage::class);
    }

    public function boot(): void
    {
        parent::boot();
        Filament::serving(function () {
            $panelId = Filament::getCurrentPanel()->getId();
            if ($panelId == 'admin') {
                ModuleAdmin::registerLiveEditSettingsUrl('editor/reset_content', ResetContentModuleSettingsPage::getUrl());
            }
        });

    }

}
