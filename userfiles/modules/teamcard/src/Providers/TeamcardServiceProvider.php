<?php

namespace MicroweberPackages\Modules\Teamcard\Providers;

use Livewire\Livewire;
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
        ModuleAdmin::registerSettingsComponent('teamcard', TeamcardSettingsComponent::class);
 

    }
}
