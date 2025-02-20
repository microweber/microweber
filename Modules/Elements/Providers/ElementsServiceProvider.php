<?php

namespace Modules\Elements\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;


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
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        // $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        // $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));


        // Register filament page for Microweber module settings
        // FilamentRegistry::registerPage(ElementsModuleSettings::class);

        // Register Microweber module
        Microweber::module(\Modules\Elements\Microweber\TitleElementModule::class);
        Microweber::module(\Modules\Elements\Microweber\TextElementModule::class);
        Microweber::module(\Modules\Elements\Microweber\PictureElementModule::class);
        Microweber::module(\Modules\Elements\Microweber\EmptyElementModule::class);
        Microweber::module(\Modules\Elements\Microweber\IconElementModule::class);
        Microweber::module(\Modules\Elements\Microweber\InlineTableElementModule::class);
        Microweber::module(\Modules\Elements\Microweber\MultipleColumnsElementModule::class);

    }

}
