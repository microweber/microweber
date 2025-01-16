<?php

namespace Modules\MailTemplate\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Support\Assets\Asset;
use Filament\Support\Facades\Filament;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use Modules\MailTemplate\Filament\Resources\MailTemplateResource;
use Modules\MailTemplate\Filament\MailTemplateModuleSettings;
use Modules\MailTemplate\Services\MailTemplateService;

class MailTemplateServiceProvider extends BaseModuleServiceProvider
{

    protected string $moduleName = 'MailTemplate';
    protected string $moduleNameLower = 'mail_template';

    public function boot(): void
    {


    }

    public function register(): void
    {

        {
            $this->registerTranslations();
            $this->registerConfig();
            $this->registerViews();
            $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));

            // Register Filament Resources and Pages
            //   FilamentRegistry::registerResource(MailTemplateResource::class);
            FilamentRegistry::registerResource(MailTemplateResource::class);

            // Register the MailTemplateService as a singleton
            $this->app->singleton(MailTemplateService::class, function ($app) {
                return new MailTemplateService();
            });

            // Bind the service to the container with a shorthand name
            $this->app->bind('mail_templates', function ($app) {
                return $app->make(MailTemplateService::class);
            });
        }


    }
}
