<?php

namespace Modules\ContentField\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use MicroweberPackages\Multilanguage\TranslateManager;
use MicroweberPackages\Option\TranslateTables\TranslateOption;
use Modules\ContentField\Filament\ContentFieldModuleSettings;
use Modules\ContentField\Microweber\ContentFieldModule;
use Modules\ContentField\TranslateTables\TranslateContentField;

class ContentFieldServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'ContentField';

    protected string $moduleNameLower = 'contentfield';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        if(app()->bound('translate_manager')) {
            app()->translate_manager->addTranslateProvider(TranslateContentField::class);
        }


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
       // $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));


    }

}
