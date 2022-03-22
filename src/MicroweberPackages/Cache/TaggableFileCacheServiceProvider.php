<?php

namespace MicroweberPackages\Cache;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class TaggableFileCacheServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->booting(function () {

            Cache::extend('file', function ($app, $config) {

                $locale = app()->getLocale();
                if ($locale) {
                    $folder = app()->environment() . '-' . $locale . DIRECTORY_SEPARATOR;
                } else {
                    $folder = app()->environment() . DIRECTORY_SEPARATOR;
                }

                $configPath = $config['path'] . DIRECTORY_SEPARATOR . $folder;

                $filesystem =new TaggableFilesystemManager();

                return \Cache::repository(new TaggableFileStore($filesystem, $configPath, $config));
            });

        });
    }

}
