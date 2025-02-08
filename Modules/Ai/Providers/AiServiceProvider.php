<?php

namespace Modules\Ai\Providers;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use Modules\Ai\Services\AiService;
use Modules\Ai\Services\Contracts\AiServiceInterface;

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
    }


    public function provides(): array
    {
        return [
            AiServiceInterface::class,
        ];
    }
}
