<?php

namespace MicroweberPackages\Modules\Layouts\PreviewAll\Providers;

use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Modules\Layouts\PreviewAll\Http\Livewire\LayoutsPreviewAllComponent;

class LayoutsPreviewAllServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-layouts-preview-all');
        $package->hasViews('microweber-module-layouts-preview-all');
    }

    public function register(): void
    {
        parent::register();
        Livewire::component('microweber-module-layouts-preview-all', LayoutsPreviewAllComponent::class);
        ModuleAdmin::registerSettings('layouts-preview-all', 'microweber-module-layouts-preview-all::settings');

    }

}
