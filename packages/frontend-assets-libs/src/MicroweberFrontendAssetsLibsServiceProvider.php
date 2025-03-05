<?php

namespace MicroweberPackages\FrontendAssetsLibs;


use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\LaravelPackageTools\Package;

class MicroweberFrontendAssetsLibsServiceProvider extends PackageServiceProvider
{

    public function configurePackage(Package $package): void
    {
        $package
            ->hasAssets()
            ->name('microweber-packages/frontend-assets-libs');
    }


    public function register(): void
    {
        parent::register();
        if(is_dir( __DIR__ . '/../resources/dist')) {

            $this->publishes([
                __DIR__ . '/../resources/dist' => public_path('vendor/microweber-packages/frontend-assets-libs'),
            ], 'public');
        }

    }

}
