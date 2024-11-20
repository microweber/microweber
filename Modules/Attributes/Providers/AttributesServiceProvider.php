<?php

namespace Modules\Attributes\Providers;

use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use Modules\Attributes\Repositories\AttributesManager;


class AttributesServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Attributes';

    protected string $moduleNameLower = 'attributes';

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
        /**
         * @property AttributesManager $attributes_manager
         */
        $this->app->singleton('attributes_manager', function ($app) {
            return new AttributesManager();
        });


    }

}
