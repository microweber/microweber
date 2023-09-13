<?php

namespace MicroweberPackages\Modules\ExampleUi\Providers;

use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Modules\ExampleUi\Http\Livewire\ExampleUiSettingsComponent;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;


class ExampleUiServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-example-ui');
        $package->hasViews('microweber-module-example-ui');
    }


    public function register(): void
    {
        parent::register();

        ModuleAdmin::registerSettingsComponent('example_ui', ExampleUiSettingsComponent::class);

    }
}
