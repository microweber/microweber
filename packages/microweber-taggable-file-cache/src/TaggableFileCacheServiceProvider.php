<?php

namespace MicroweberPackages\TaggableFileCache;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use MicroweberPackages\TaggableFileCache\Console\ClearTaggableFileCacheCommand;

class TaggableFileCacheServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->afterResolving('cache', function () {

            Cache::extend('file', function ($app, $config) {

                $environment = $app->environment();

                // Scope the cache as a nested <env>/<locale> directory, e.g. production/en_US.
                // The locale is a sub-folder of the environment so that clearing the whole
                // environment also clears every locale beneath it.
                $locale = $app->getLocale();
                if ($locale) {
                    $folder = $environment . DIRECTORY_SEPARATOR . $locale . DIRECTORY_SEPARATOR;
                } else {
                    $folder = $environment . DIRECTORY_SEPARATOR;
                }

                $configPath = $config['path'] . DIRECTORY_SEPARATOR . $folder;

                $filesystem = new TaggableFilesystemManager();

                return Cache::repository(new TaggableFileStore($filesystem, $configPath, $config));
            });

        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ClearTaggableFileCacheCommand::class,
            ]);
        }
    }
}