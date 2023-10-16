<?php

namespace MicroweberPackages\Modules\UnlockPremiumLayout\Providers;

use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Modules\UnlockPremiumLayout\Http\Livewire\UnlockPremiumLayoutSettingsComponent;

class UnlockPremiumLayoutServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-unlock-premium-layout');
        $package->hasViews('microweber-module-unlock-premium-layout');
    }

    public function register(): void
    {
        parent::register();
        Livewire::component('microweber-module-unlock-premium-layout::settings', UnlockPremiumLayoutSettingsComponent::class);
        ModuleAdmin::registerSettings('unlock_premium_layout', 'microweber-module-unlock-premium-layout::settings');

    }

}
