<?php

namespace MicroweberPackages\FrontendAssets\Providers;


use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\LaravelPackageTools\Package;

class MicroweberFrontendAssetsServiceProvider extends PackageServiceProvider
{

    public function configurePackage(Package $package): void
    {
        $package
            ->hasAssets()
            ->name('microweber-packages/frontend-assets');
    }


    public function register(): void
    {

        $this->publishes([
            __DIR__ . '/../../resources/assets' => public_path('vendor/microweber-packages/frontend-assets'),
        ], 'public');

        parent::register();


    }

}
