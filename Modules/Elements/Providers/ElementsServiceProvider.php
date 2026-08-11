<?php

namespace Modules\Elements\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
use MicroweberPackages\ModuleRegistry\Facades\ModuleRegistry;


class ElementsServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Elements';

    protected string $moduleNameLower = 'elements';

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
        parent::register();

        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        // $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        // $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));


        // Register filament page for Microweber module settings
        // FilamentRegistry::registerPage(ElementsModuleSettings::class);

        // Register Microweber module
        ModuleRegistry::module(\Modules\Elements\Microweber\TitleElementModule::class);
        ModuleRegistry::module(\Modules\Elements\Microweber\TextElementModule::class);
        ModuleRegistry::module(\Modules\Elements\Microweber\PictureElementModule::class);
        ModuleRegistry::module(\Modules\Elements\Microweber\EmptyElementModule::class);
        ModuleRegistry::module(\Modules\Elements\Microweber\IconElementModule::class);
        ModuleRegistry::module(\Modules\Elements\Microweber\InlineTableElementModule::class);
        ModuleRegistry::module(\Modules\Elements\Microweber\MultipleColumnsElementModule::class);

    }

}
