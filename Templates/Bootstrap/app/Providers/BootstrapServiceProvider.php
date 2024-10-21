<?php

namespace Templates\Bootstrap\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use MicroweberPackages\LaravelTemplates\Providers\BaseTemplateServiceProvider;
use MicroweberPackages\Package\ModulePackage;
use Templates\Bootstrap\View\Components\Alert;
use Templates\Bootstrap\View\Components\Card;
use Templates\Bootstrap\View\Components\Col;
use Templates\Bootstrap\View\Components\Columns;
use Templates\Bootstrap\View\Components\Container;
use Templates\Bootstrap\View\Components\Row;

class BootstrapServiceProvider extends BaseTemplateServiceProvider
{
    protected string $moduleName = 'Bootstrap';

    protected string $moduleNameLower = 'bootstrap';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        Blade::component('bootstrap-container', Container::class);
        Blade::component('bootstrap-row', Row::class);
        Blade::component('bootstrap-col', Col::class);
        Blade::component('bootstrap-card', Card::class);
        Blade::component('bootstrap-alert', Alert::class);

//        Blade::componentNamespace('Templates\\Bootstrap\\Views\\Components', 'bootstrap');
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
