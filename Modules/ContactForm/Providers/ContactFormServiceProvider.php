<?php

namespace Modules\ContactForm\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\ContactForm\Filament\ContactFormModuleSettings;
use Modules\ContactForm\Microweber\ContactFormModule;

class ContactFormServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'ContactForm';

    protected string $moduleNameLower = 'contact_form';

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
        FilamentRegistry::registerPage(ContactFormModuleSettings::class);

        // Register Microweber module
        Microweber::module('contact_form', \Modules\ContactForm\Microweber\ContactFormModule::class);

    }

}
