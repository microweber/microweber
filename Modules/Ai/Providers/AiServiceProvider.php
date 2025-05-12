<?php

namespace Modules\Ai\Providers;

use Illuminate\Support\Facades\Config;
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

    public function boot(): void
    {
        if (mw_is_installed()) {

            $openAiModel = get_option('openai_model', 'ai');
            $openAiApiKey = get_option('openai_api_key', 'ai');
            $openRouterModel = get_option('openrouter_model', 'ai');
            $openRouterApiKey = get_option('openrouter_api_key', 'ai');
            $ollamaModel = get_option('ollama_model', 'ai');
            $ollamaBaseUrl = get_option('ollama_base_url', 'ai');
            $geminiModel = get_option('gemini_model', 'ai');
            $geminiApiKey = get_option('gemini_api_key', 'ai');

            if ($openAiModel) {
                Config::set('modules.ai.openai_model', $openAiModel);
            }
            if ($openAiApiKey) {
                Config::set('modules.ai.openai_api_key', $openAiApiKey);
            }

            if ($openRouterModel) {
                Config::set('modules.ai.openrouter_model', $openRouterModel);
            }
            if ($openRouterApiKey) {
                Config::set('modules.ai.openrouter_api_key', $openRouterApiKey);
            }

            if ($ollamaModel) {
                Config::set('modules.ai.ollama_model', $ollamaModel);
            }
            if ($ollamaBaseUrl) {
                Config::set('modules.ai.ollama_base_url', $ollamaBaseUrl);
            }

        }

    }

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
