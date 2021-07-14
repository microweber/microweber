<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\Media;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

class MediaManagerServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        $this->loadMigrationsFrom(__DIR__ . '/migrations/');
        /**
         * @property \MicroweberPackages\Media\MediaManager    $media_manager
         */
        $this->app->singleton('media_manager', function ($app) {
            return new MediaManager();
        });


        Config::set('filesystems.disks.media', [
            'driver' => 'local',
            'root' => media_uploads_path(),
            'url' => media_uploads_url(),
            'visibility' => 'public',
        ]);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['media_manager'];
    }

}
