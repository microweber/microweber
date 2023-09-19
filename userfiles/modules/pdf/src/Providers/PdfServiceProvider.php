<?php

namespace MicroweberPackages\Modules\Pdf\Providers;

use Livewire\Livewire;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MicroweberPackages\Modules\Pdf\Http\Livewire\PdfSettingsComponent;

class PdfServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-pdf');
        $package->hasViews('microweber-module-pdf');
    }

    public function register(): void
    {
        parent::register();
        Livewire::component('microweber-module-pdf::settings', PdfSettingsComponent::class);
        ModuleAdmin::registerSettings('pdf', 'microweber-module-pdf::settings');

    }

}
