<?php

namespace Modules\Audio\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Microweber\Facades\Microweber;
use MicroweberPackages\Module\Http\Controllers\ModuleController;
use Modules\Audio\Filament\AudioModuleSettings;

class AudioServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Audio';

    protected string $moduleNameLower = 'audio';

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

        FilamentRegistry::registerPage(AudioModuleSettings::class);
//    use BladeUI\Icons\Factory;
//
//        $this->callAfterResolving(Factory::class, function (Factory $factory) {
//            $factory->add('heroicons', [
//                'path' => DIR.'/../resources/svg',
//                'prefix' => 'heroicon',
//            ]);
//        });
//    }

        Microweber::module('audio', \Modules\Audio\Microweber\AudioModule::class);


    }

}
