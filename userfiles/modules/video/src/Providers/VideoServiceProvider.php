<?php

namespace MicroweberPackages\Modules\Video\Providers;

use Livewire\Livewire;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MicroweberPackages\Modules\Video\Http\Livewire\VideoSettingsComponent;

class VideoServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-video');
        $package->hasViews('microweber-module-video');
    }

    public function register(): void
    {
        parent::register();

        Livewire::component('microweber-module-video::settings', VideoSettingsComponent::class);
        ModuleAdmin::registerSettings('video', 'microweber-module-video::settings');

    }

}
