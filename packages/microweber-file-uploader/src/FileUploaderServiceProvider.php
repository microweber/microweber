<?php

namespace MicroweberPackages\FileUploader;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\FileUploader\Validation\FileValidationService;

class FileUploaderServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/file-uploader.php', 'file-uploader');

        $this->app->singleton(FileUploaderService::class, function () {
            return new FileUploaderService(
                new FileValidationService()
            );
        });
        $this->app->alias(FileUploaderService::class, 'file_uploader');

        // Also expose the uploader's own validation service for direct use.
        $this->app->singleton(FileValidationService::class, function ($app) {
            return $app->make(FileUploaderService::class)->validator();
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/file-uploader.php' => config_path('file-uploader.php'),
            ], 'file-uploader-config');
        }
    }
}