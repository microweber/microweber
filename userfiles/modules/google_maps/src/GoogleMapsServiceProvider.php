<?php


namespace MicroweberPackages\Modules\GoogleMaps;


use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use MicroweberPackages\LiveEdit\Events\ServingLiveEdit;
use MicroweberPackages\LiveEdit\Events\ServingModuleSettings;
use MicroweberPackages\Modules\GoogleMaps\Http\Livewire\GoogleMapsSettingsComponent;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;


class GoogleMapsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-module-google-maps');
    }

    public function register(): void
    {
        parent::register();
        Event::listen(ServingLiveEdit::class, [$this, 'registerLiveEditAssets']);
        Event::listen(ServingModuleSettings::class, [$this, 'registerLivewireComponents']);

        Livewire::component('microweber-live-edit::google_maps', GoogleMapsSettingsComponent::class);

        // $this->loadRoutesFrom(__DIR__ . '/routes/live_edit.php');
        View::addNamespace('modules.google_maps', __DIR__ . '/resources/views');
    }

    public function registerLiveEditAssets(ServingLiveEdit $event): void
    {

    }

    public function registerLivewireComponents(ServingModuleSettings $event): void
    {

    }

}
