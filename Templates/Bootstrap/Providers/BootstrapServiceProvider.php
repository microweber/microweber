<?php

namespace Templates\Bootstrap\Providers;

use Illuminate\Support\Facades\Blade;
use MicroweberPackages\LaravelTemplates\Providers\BaseTemplateServiceProvider;
use Modules\Components\View\Components\Alert;
use Modules\Components\View\Components\Button;
use Modules\Components\View\Components\Card;
use Modules\Components\View\Components\Checkbox;
use Modules\Components\View\Components\Col;
use Modules\Components\View\Components\Container;
use Modules\Components\View\Components\Hero;
use Modules\Components\View\Components\Input;
use Modules\Components\View\Components\Navbar;
use Modules\Components\View\Components\NavItem;
use Modules\Components\View\Components\Radio;
use Modules\Components\View\Components\Row;
use Modules\Components\View\Components\Section;
use Modules\Components\View\Components\SimpleText;

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


    }

}
