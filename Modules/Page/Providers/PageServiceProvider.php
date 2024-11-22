<?php

namespace Modules\Page\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Page\Filament\PageModuleSettings;
use Modules\Page\Filament\Resources\PageResource;
use Modules\Page\Microweber\PageModule;

class PageServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Page';

    protected string $moduleNameLower = 'page';

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

        FilamentRegistry::registerResource(PageResource::class);
        FilamentRegistry::registerPage(PageModuleSettings::class);
        Microweber::module(\Modules\Page\Microweber\PageModule::class);

    }

}
