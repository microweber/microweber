<?php

namespace Templates\Bootstrap\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use MicroweberPackages\LaravelTemplates\Providers\BaseTemplateServiceProvider;
use MicroweberPackages\Package\ModulePackage;

class BootstrapServiceProvider extends BaseTemplateServiceProvider
{
    protected string $moduleName = 'Bootstrap';

    protected string $moduleNameLower = 'bootstrap';

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
        $this->registerConfig();
        $this->registerViews();
    }

}
