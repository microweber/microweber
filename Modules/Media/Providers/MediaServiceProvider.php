<?php

namespace Modules\Media\Providers;

use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use Modules\Media\Repositories\MediaManager;
use Modules\Media\Repositories\MediaRepository;


class MediaServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Media';

    protected string $moduleNameLower = 'media';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {


    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/api.php'));


        /**
         * @property MediaRepository $media_repository
         */
        $this->app->bind('media_repository', function () {
            return new MediaRepository();
        });
        /**
         * @property \Modules\Media\Repositories\MediaManager $media_manager
         */
        $this->app->singleton('media_manager', function ($app) {
            return new MediaManager();
        });

        // Register filament page for Microweber module settings
        // FilamentRegistry::registerPage(MediaModuleSettings::class);

        // Register Microweber module
        // Microweber::module(\Modules\Media\Microweber\MediaModule::class);

    }

}
