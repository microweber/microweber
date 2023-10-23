<?php

namespace MicroweberPackages\Modules\UnlockPackage\Providers;

use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Modules\UnlockPackage\Http\Livewire\UnlockPackageSettingsComponent;

class UnlockPackageServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-unlock-package');
        $package->hasViews('microweber-module-unlock-package');
    }

    public function register(): void
    {
        parent::register();
        Livewire::component('microweber-module-unlock-package::settings', UnlockPackageSettingsComponent::class);
        ModuleAdmin::registerSettings('unlock-package', 'microweber-module-unlock-package::settings');

    }

}
