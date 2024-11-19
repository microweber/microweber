<?php

namespace Modules\Post\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Post\Filament\Admin\Resources\PostResource;
use Modules\Post\Filament\PostModuleSettings;
use Modules\Post\Microweber\PostModule;

class PostServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Post';

    protected string $moduleNameLower = 'post';

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
        // $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));

        FilamentRegistry::registerResource(PostResource::class);
        // Register filament page for Microweber module settings
         FilamentRegistry::registerPage(PostModuleSettings::class);

        // Register Microweber module
          Microweber::module(\Modules\Post\Microweber\PostModule::class);
    }
}
