<?php

namespace MicroweberPackages\Modules\HighlightCode\Providers;

use Livewire\Livewire;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MicroweberPackages\Modules\HighlightCode\Http\Livewire\HighlightCodeSettingsComponent;

class HighlightCodeServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-highlight-code');
        $package->hasViews('microweber-module-highlight-code');
    }

    public function register(): void
    {
        parent::register();
        Livewire::component('microweber-module-highlight-code::settings', HighlightCodeSettingsComponent::class);

        ModuleAdmin::registerSettings('highlight_code', 'microweber-module-highlight-code::settings');

    }

}
