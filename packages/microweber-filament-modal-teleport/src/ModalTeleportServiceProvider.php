<?php

declare(strict_types=1);

namespace MicroweberPackages\FilamentModalTeleport;


use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use Spatie\LaravelPackageTools\Package;
class ModalTeleportServiceProvider extends MicroweberPackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-packages/filament-modal-teleport');
    }

    public function packageRegistered(): void
    {
        //
    }

    public function packageBooted(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'mw-modal-teleport');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../resources/css' => public_path('vendor/mw-modal-teleport/css'),
            ], 'mw-modal-teleport-assets');
        }
    }
}
