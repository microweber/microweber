<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 3/11/2021
 * Time: 3:49 PM
 */

namespace MicroweberPackages\Assets;

use Fisharebest\LaravelAssets\AssetsServiceProvider as LaravelAssetsServiceProvider;
use Fisharebest\LaravelAssets\Commands\Purge;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Adapter\Local;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Filesystem;

class AssetsServiceProvider extends LaravelAssetsServiceProvider
{
    /**
     * Register bindings in the container.
     */
    public function register()
    {
        // Merge our default configuration.
        $this->mergeConfigFrom(__DIR__ . '/config/assets.php', 'assets');
        $assets = config('assets');


        // for CDN
        if (isset($assets['use_cdn']) and $assets['use_cdn']) {

            $this->mergeConfigFrom(__DIR__ . '/config/assets.cdn.php', 'assets.cdn');
            $assets = config('assets');

            if (isset($assets['cdn']['collections'])) {
                $assets['collections'] = array_merge($assets['collections'], $assets['cdn']['collections']);
                unset($assets['cdn']['collections']);
            }
        }


        // Bind our component into the IoC container.
        $this->app->singleton('assets', function ($app) use ($assets) {
            $config = [
                'visibility' => AdapterInterface::VISIBILITY_PUBLIC,
            ];
            $filesystem = new Filesystem(new Local(public_path()), $config);
            $assets = new Assets($assets, $filesystem);
            return $assets;
        });
        $this->app->alias('Assets', Assets::class);

      /*
      TODO
      if (is_cli()) {
            // Command-line functions
            // Don't use array access here - it is hard to mock / unit-test.  Use bind() and make() instead.
            $this->app->bind('command.assets.purge', function (Application $app) {
                return new Purge($app->make('assets'));
            });
            $this->commands(['command.assets.purge']);
        }*/
    }


}
