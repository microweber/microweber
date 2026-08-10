<?php

namespace Templates\Big\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
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

class BigServiceProvider extends BaseTemplateServiceProvider
{
    protected string $moduleName = 'Big';

    protected string $moduleNameLower = 'big';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {

   //     View::prependNamespace('modules.layouts', __DIR__ . '/../resources/views/modules/layouts');

        // Register this template's design-system adapter (each template registers
        // its own adapter; the core DesignSystemService no longer hardcodes them).
        if (app()->bound('design_system')) {
            app('design_system')->registerAdapter(
                new \Templates\Big\DesignSystem\BigTemplateVarsAdapter()
            );
        }

    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        parent::register();

        $this->registerConfig();
        $this->registerViews();

        //Blade::componentNamespace('Templates\\Bootstrap\\Views\\Components', 'bootstrap');

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

    }

}
