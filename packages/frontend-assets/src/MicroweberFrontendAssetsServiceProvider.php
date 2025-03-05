<?php

namespace MicroweberPackages\FrontendAssets;


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
        parent::register();
        if(is_dir( __DIR__ . '/../resources/dist')) {

            $this->publishes([
                __DIR__ . '/../resources/dist' => public_path('vendor/microweber-packages/frontend-assets'),
            ], 'public');

        }

    }

}
