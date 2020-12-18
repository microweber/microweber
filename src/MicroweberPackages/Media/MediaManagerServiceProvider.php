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

class MediaManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
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
}