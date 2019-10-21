<?php

namespace Microweber\Packages\GoogleMaps\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class GoogleMapsServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        mw()->modules_manager->register('google_maps', ' Microweber\Packages\GoogleMaps\Http\Controllers\GoogleMapsController@index');
        mw()->modules_manager->register('google_maps/admin', ' Microweber\Packages\GoogleMaps\Http\Controllers\GoogleMapsController@admin');
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('googlemaps.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'googlemaps'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/googlemaps');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/googlemaps';
        }, \Config::get('view.paths')), [$sourcePath]), 'googlemaps');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/googlemaps');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'googlemaps');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'googlemaps');
        }
    }

    /**
     * Register an additional directory of factories.
     * @source https://github.com/sebastiaanluca/laravel-resource-flow/blob/develop/src/Modules/ModuleServiceProvider.php#L66
     */
    public function registerFactories()
    {
        if (! app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/Database/factories');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
