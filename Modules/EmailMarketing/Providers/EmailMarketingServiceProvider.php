<?php

namespace Modules\EmailMarketing\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\EmailMarketing\Console\ProcessCampaigns;


class EmailMarketingServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'EmailMarketing';

    protected string $moduleNameLower = 'emailmarketing';

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


        // Register filament page for Microweber module settings
        // FilamentRegistry::registerPage(EmailMarketingModuleSettings::class);

        // Register Microweber module
        // Microweber::module(\Modules\EmailMarketing\Microweber\EmailMarketingModule::class);

        $this->commands([
            ProcessCampaigns::class
        ]);
    }

}
