<?php

namespace Modules\Ai\Providers;

use Illuminate\Support\Facades\Config;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\LiveEdit\Facades\LiveEditManager;
use Modules\Ai\Filament\Pages\AiSettingsPage;
use Modules\Ai\Services\AiService;
use Modules\Ai\Services\Drivers\AiServiceInterface;

class AiServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Ai';

    protected string $moduleNameLower = 'ai';

    public function boot(): void
    {
        if (mw_is_installed()) {
            $defaultDriver = get_option('default_driver', 'ai');
            if ($defaultDriver) {
                Config::set('modules.ai.default_driver', $defaultDriver);
            }
            $openAiModel = get_option('openai_model', 'ai');
            $openAiApiKey = get_option('openai_api_key', 'ai');
            $openRouterModel = get_option('openrouter_model', 'ai');
            $openRouterApiKey = get_option('openrouter_api_key', 'ai');
            $ollamaModel = get_option('ollama_model', 'ai');
            $ollamaBaseUrl = get_option('ollama_base_url', 'ai');
            $geminiModel = get_option('gemini_model', 'ai');
            $geminiApiKey = get_option('gemini_api_key', 'ai');

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
            if ($ollamaBaseUrl) {
                Config::set('modules.ai.drivers.ollama.base_url', $ollamaBaseUrl);
            }

        }
        // Register the AI service as a singleton
        $this->app->singleton('ai', function ($app) {
            return new AiService(
                config('modules.ai.default_driver'),
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
}
