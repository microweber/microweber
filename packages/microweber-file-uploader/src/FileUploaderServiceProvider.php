<?php

namespace MicroweberPackages\FileUploader;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\FileUploader\Validation\FileValidationService;

class FileUploaderServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/file-uploader.php', 'file-uploader');

        $this->app->singleton('file_uploader', function ($app) {
            return new FileUploaderService(
                new FileValidationService()
            );
        });

        // Also bind the validation service separately for direct use
        $this->app->singleton(FileValidationService::class, function ($app) {
            return $app->make('file_uploader')->validator();
        });

        $this->app->singleton(FileUploaderService::class, function ($app) {
            return $app->make('file_uploader');
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