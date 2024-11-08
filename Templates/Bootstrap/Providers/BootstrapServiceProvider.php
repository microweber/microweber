<?php

namespace Templates\Bootstrap\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use MicroweberPackages\LaravelTemplates\Providers\BaseTemplateServiceProvider;
use MicroweberPackages\Package\ModulePackage;
use Templates\Bootstrap\View\Components\Alert;
use Templates\Bootstrap\View\Components\Button;
use Templates\Bootstrap\View\Components\Card;
use Templates\Bootstrap\View\Components\Col;
use Templates\Bootstrap\View\Components\Columns;
use Templates\Bootstrap\View\Components\Container;
use Templates\Bootstrap\View\Components\Hero;
use Templates\Bootstrap\View\Components\Navbar;
use Templates\Bootstrap\View\Components\NavItem;
use Templates\Bootstrap\View\Components\Row;
use Templates\Bootstrap\View\Components\SimpleText;

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
        //Blade::componentNamespace('Templates\\Bootstrap\\Views\\Components', 'bootstrap');
        Blade::component('bootstrap-hero', Hero::class);
        Blade::component('bootstrap-simple-text', SimpleText::class);
        Blade::component('bootstrap-container', Container::class);
        Blade::component('bootstrap-row', Row::class);
        Blade::component('bootstrap-col', Col::class);
        Blade::component('bootstrap-card', Card::class);
        Blade::component('bootstrap-alert', Alert::class);
        Blade::component('bootstrap-button', Button::class);
        Blade::component('bootstrap-navbar', Navbar::class);
        Blade::component('bootstrap-nav-item', NavItem::class);

    }

}
