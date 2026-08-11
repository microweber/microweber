<?php

namespace Modules\ContactForm\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
use MicroweberPackages\ModuleRegistry\Facades\ModuleRegistry;
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
        parent::register();

        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/api.php'));


        template_head(function () {
             $js = asset('modules/contact_form/js/contact-form-alpine.js');
                        return <<<HTML
             <script src="{$js}" id="mw-contact-form-alpine-js"></script>
HTML;
        });


        // Register filament page for Microweber module settings
        FilamentRegistry::registerPage(ContactFormModuleSettings::class);

        // Register Microweber module
        ModuleRegistry::module(ContactFormModule::class);

    }

}
