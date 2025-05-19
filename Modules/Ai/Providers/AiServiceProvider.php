<?php

namespace Modules\Ai\Providers;

use Illuminate\Support\Facades\Config;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\LiveEdit\Facades\LiveEditManager;
use Modules\Ai\Filament\Pages\AiSettingsPage;
use Modules\Ai\Services\AiService;
use Modules\Ai\Services\AiServiceImages;
use Modules\Ai\Services\Drivers\AiServiceInterface;

class AiServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Ai';

    protected string $moduleNameLower = 'ai';


    public function boot(): void
    {
        $this->setAiConfig();

        // Register the agent factory
        $this->app->singleton('ai.agents', function($app) {
            return new \Modules\Ai\Services\AgentFactory($app);
        });

        // Register standard agents
        $this->app->make('ai.agents')->register('base',
            \Modules\Ai\Agents\BaseAgent::class);


        $this->app->make('ai.agents')->register('content',
            \Modules\Ai\Agents\ContentAgent::class);
        $this->app->make('ai.agents')->register('shop',
            \Modules\Ai\Agents\ShopAgent::class);

        // Register the AI service as a singleton
        $this->app->singleton('ai', function ($app) {
            return new AiService(
                config('modules.ai.default_driver'),
                config('modules.ai.drivers')
            );
        });

        // Register the AI image service as a singleton
        $this->app->singleton('ai.images', function ($app) {
            return new AiServiceImages(
                config('modules.ai.default_driver_images'),
                config('modules.ai.drivers')
            );
        });
    }

    public function register(): void
    {
        $this->registerConfig();
        $this->registerViews();
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        FilamentRegistry::registerPage(AiSettingsPage::class);

        LiveEditManager::addScript('mw-ai', asset('modules/ai/js/mw-ai.js'));
    }

    public function provides(): array
    {
        return [
            AiServiceInterface::class,
        ];
    }


    public function setAiConfig(): void
    {
        if (mw_is_installed()) {
            // Load general settings
            $defaultDriver = get_option('default_driver', 'ai');
            if ($defaultDriver) {
                Config::set('modules.ai.default_driver', $defaultDriver);
            }

            $defaultDriverImages = get_option('default_driver_images', 'ai');
            if ($defaultDriverImages) {
                Config::set('modules.ai.default_driver_images', $defaultDriverImages);
            }

            // Load driver enabled states
            $openAiEnabled = get_option('openai_enabled', 'ai');
            if ($openAiEnabled !== null) {
                Config::set('modules.ai.drivers.openai.enabled', (bool)$openAiEnabled);
            }

            $geminiEnabled = get_option('gemini_enabled', 'ai');
            if ($geminiEnabled !== null) {
                Config::set('modules.ai.drivers.gemini.enabled', (bool)$geminiEnabled);
            }

            $openRouterEnabled = get_option('openrouter_enabled', 'ai');
            if ($openRouterEnabled !== null) {
                Config::set('modules.ai.drivers.openrouter.enabled', (bool)$openRouterEnabled);
            }

            $ollamaEnabled = get_option('ollama_enabled', 'ai');
            if ($ollamaEnabled !== null) {
                Config::set('modules.ai.drivers.ollama.enabled', (bool)$ollamaEnabled);
            }

            $replicateEnabled = get_option('replicate_enabled', 'ai');
            if ($replicateEnabled !== null) {
                Config::set('modules.ai.drivers.replicate.enabled', (bool)$replicateEnabled);
            }

            // Load driver-specific settings
            $openAiModel = get_option('openai_model', 'ai');
            $openAiApiKey = get_option('openai_api_key', 'ai');
            $openRouterModel = get_option('openrouter_model', 'ai');
            $openRouterApiKey = get_option('openrouter_api_key', 'ai');
            $ollamaModel = get_option('ollama_model', 'ai');
            $ollamaApiUrl = get_option('ollama_api_url', 'ai');
            $geminiModel = get_option('gemini_model', 'ai');
            $geminiApiKey = get_option('gemini_api_key', 'ai');
            $replicateApiToken = get_option('replicate_api_key', 'ai');
            $replicateImageModel = get_option('replicate_model', 'ai');

            if ($openAiModel) {
                Config::set('modules.ai.drivers.openai.model', $openAiModel);
            }
            if ($openAiApiKey) {
                Config::set('modules.ai.drivers.openai.api_key', $openAiApiKey);
            }

            if ($openRouterModel) {
                Config::set('modules.ai.drivers.openrouter.model', $openRouterModel);
            }
            if ($openRouterApiKey) {
                Config::set('modules.ai.drivers.openrouter.api_key', $openRouterApiKey);
            }

            if ($ollamaModel) {
                Config::set('modules.ai.drivers.ollama.model', $ollamaModel);
            }
            if ($ollamaApiUrl) {
                Config::set('modules.ai.drivers.ollama.url', $ollamaApiUrl);
            }

            if ($geminiModel) {
                Config::set('modules.ai.drivers.gemini.model', $geminiModel);
            }
            if ($geminiApiKey) {
                Config::set('modules.ai.drivers.gemini.api_key', $geminiApiKey);
            }

            if ($replicateApiToken) {
                Config::set('modules.ai.drivers.replicate.api_key', $replicateApiToken);
            }
            if ($replicateImageModel) {
                Config::set('modules.ai.drivers.replicate.model', $replicateImageModel);
            }
        }
    }
}
