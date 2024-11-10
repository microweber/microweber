<?php

namespace Modules\Components\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Components\Filament\ComponentsModuleSettings;
use Modules\Components\Microweber\ComponentsModule;
use Modules\Components\View\Components\Alert;
use Modules\Components\View\Components\Button;
use Modules\Components\View\Components\Card;
use Modules\Components\View\Components\Checkbox;
use Modules\Components\View\Components\Col;
use Modules\Components\View\Components\Container;
use Modules\Components\View\Components\Hero;
use Modules\Components\View\Components\Input;
use Modules\Components\View\Components\Modal;
use Modules\Components\View\Components\Navbar;
use Modules\Components\View\Components\NavItem;
use Modules\Components\View\Components\Pagination;
use Modules\Components\View\Components\ProgressBar;
use Modules\Components\View\Components\Radio;
use Modules\Components\View\Components\Row;
use Modules\Components\View\Components\Section;
use Modules\Components\View\Components\Select;
use Modules\Components\View\Components\SimpleText;
use Modules\Components\View\Components\TabPane;
use Modules\Components\View\Components\Tabs;

class ComponentsServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Components';

    protected string $moduleNameLower = 'components';

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

        Blade::component('hero', Hero::class);
        Blade::component('input', Input::class);
        Blade::component('checkbox', Checkbox::class);
        Blade::component('radio', Radio::class);
        Blade::component('simple-text', SimpleText::class);
        Blade::component('section', Section::class);
        Blade::component('container', Container::class);
        Blade::component('row', Row::class);
        Blade::component('col', Col::class);
        Blade::component('card', Card::class);
        Blade::component('alert', Alert::class);
        Blade::component('button', Button::class);
        Blade::component('navbar', Navbar::class);
        Blade::component('nav-item', NavItem::class);
        Blade::component('modal', Modal::class);
        Blade::component('select', Select::class);
        Blade::component('progress-bar', ProgressBar::class);
        Blade::component('tabs', Tabs::class);
        Blade::component('tab-pane', TabPane::class);
        Blade::component('pagination', Pagination::class);
    }
}
