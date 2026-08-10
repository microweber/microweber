<?php

namespace MicroweberPackages\FileUploader;

use MicroweberPackages\FileUploader\Validation\FileValidationService;
use MicroweberPackages\Package\MicroweberPackageServiceProvider;
use Spatie\LaravelPackageTools\Package;

class FileUploaderServiceProvider extends MicroweberPackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('microweber-packages/file-uploader');
    }

    public function packageRegistered(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/file-uploader.php', 'file-uploader');

        $this->app->singleton(FileUploaderService::class, function () {
            return new FileUploaderService(
                new FileValidationService()
            );
        });
        // Also expose the uploader's own validation service for direct use.
        $this->app->singleton(FileValidationService::class, function ($app) {
            return $app->make(FileUploaderService::class)->validator();
        });
    }

    public function packageBooted(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/file-uploader.php' => config_path('file-uploader.php'),
            ], 'file-uploader-config');
        }
    }
}