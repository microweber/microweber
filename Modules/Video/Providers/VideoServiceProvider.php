<?php

namespace Modules\Video\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Video\Filament\VideoModuleSettings;
use Modules\Video\Microweber\VideoModule;

class VideoServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Video';

    protected string $moduleNameLower = 'video';

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

        FilamentRegistry::registerPage(VideoModuleSettings::class);
        Microweber::module('audio', VideoModule::class);

    }

}
