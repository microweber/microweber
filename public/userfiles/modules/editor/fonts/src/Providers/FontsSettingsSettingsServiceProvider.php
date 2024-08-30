<?php

namespace MicroweberPackages\Modules\Editor\Fonts\FontsSettings\Providers;


use Filament\Facades\Filament;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Modules\Editor\Fonts\FontsSettings\Filament\FontsManagerModuleSettingsPage;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FontsSettingsSettingsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-editor-fonts');
        $package->hasViews('microweber-module-editor-fonts');
    }

    public function register(): void
    {

        parent::register();
        FilamentRegistry::registerPage(FontsManagerModuleSettingsPage::class);


    }


    public function boot(): void
    {
        parent::boot();
        Filament::serving(function () {
            $panelId = Filament::getCurrentPanel()->getId();
            if ($panelId == 'admin') {
                ModuleAdmin::registerLiveEditSettingsUrl('editor/fonts/font-manager-modal', FontsManagerModuleSettingsPage::getUrl());
            }
        });

    }

}
