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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

use Livewire\Livewire;
use MicroweberPackages\Media\Http\Livewire\Admin\ListMediaForModel;
use MicroweberPackages\Media\Models\Media;
use MicroweberPackages\Media\Repositories\MediaRepository;

class MediaManagerServiceProvider extends ServiceProvider implements DeferrableProvider
{


    public function register()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/api.php');

        View::addNamespace('media', __DIR__ . '/resources/views');

        // add disk to config
        config(['filesystems.disks.media' => [
            'driver' => 'local',
            'root' => media_uploads_path(),
            'url' => media_uploads_url(),
            'visibility' => 'public',
        ]]);
        config(['filesystems.disks.public' => [
            'driver' => 'local',
            'root' => public_path(),
            'url' => public_asset(),
            'visibility' => 'public',
        ]]);


        $this->app->resolving(\MicroweberPackages\Repository\RepositoryManager::class, function (\MicroweberPackages\Repository\RepositoryManager $repositoryManager) {
            $repositoryManager->extend(Media::class, function () {
                return new MediaRepository();
            });
        });


        /**
         * @property MediaRepository $media_repository
         */
        $this->app->bind('media_repository', function () {
            return $this->app->repository_manager->driver(Media::class);;
        });


    }


    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        $this->loadMigrationsFrom(__DIR__ . '/migrations/');
        /**
         * @property \MicroweberPackages\Media\MediaManager $media_manager
         */
        $this->app->singleton('media_manager', function ($app) {
            return new MediaManager();
        });


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
