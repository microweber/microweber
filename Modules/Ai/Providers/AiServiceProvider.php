<?php

namespace Modules\Ai\Providers;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use Modules\Ai\Filament\Pages\AiSettingsPage;
use Modules\Ai\Services\AiService;
use Modules\Ai\Services\Contracts\AiServiceInterface;
use Modules\Settings\Filament\Pages\Settings;

class AiServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Ai';

    protected string $moduleNameLower = 'ai';


    public function register(): void
    {

        $this->registerConfig();
        $this->registerViews();

        // Register the AI service as a singleton
        $this->app->singleton(AiServiceInterface::class, function ($app) {
            return new AiService(
                config('modules.ai.default'),
                config('modules.ai.drivers')
            );
        });
        FilamentRegistry::registerPage(AiSettingsPage::class);
      //  FilamentRegistry::registerPage(AiSettingsPage::class,Settings::class);



    }


    public function provides(): array
    {
        return [
            AiServiceInterface::class,
        ];
    }
}
