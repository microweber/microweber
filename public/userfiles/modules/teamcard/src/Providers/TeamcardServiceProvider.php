<?php

namespace MicroweberPackages\Modules\Teamcard\Providers;

use Livewire\Livewire;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Modules\Teamcard\Http\Livewire\TeamcardSettingsComponent;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;


class TeamcardServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-teamcard');
        $package->hasViews('microweber-module-teamcard');
    }


    public function register(): void
    {
        parent::register();

//        Livewire::component('microweber-module-teamcard::settings', TeamcardSettingsComponent::class);
//        ModuleAdmin::registerSettings('teamcard', 'microweber-module-teamcard::settings');

        ModuleAdmin::registerLiveEditSettingsUrl('teamcard', site_url('admin-live-edit/teamcard-module-settings'));
        FilamentRegistry::registerPage(\MicroweberPackages\Modules\Teamcard\Http\Livewire\TeamcardModuleSettings::class);


    }
}
