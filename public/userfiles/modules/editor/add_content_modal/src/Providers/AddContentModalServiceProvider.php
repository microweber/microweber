<?php

namespace MicroweberPackages\Modules\Editor\AddContentModal\Providers;


use Filament\Facades\Filament;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Modules\Editor\AddContentModal\Filament\AddContentModalPage;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class AddContentModalServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-editor-add-content-modal');
        $package->hasViews('microweber-module-editor-add-content-modal');
    }

    public function register(): void
    {

        parent::register();
        FilamentRegistry::registerPage(AddContentModalPage::class);


    }


    public function boot(): void
    {
        parent::boot();
        Filament::serving(function () {
            $panelId = Filament::getCurrentPanel()->getId();
            if ($panelId == 'admin') {
                ModuleAdmin::registerLiveEditSettingsUrl('editor/add_content_modal', AddContentModalPage::getUrl());
            }
        });

    }

}
