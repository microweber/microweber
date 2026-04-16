<?php

namespace Modules\Ai\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Livewire\Livewire;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\LiveEdit\Facades\LiveEditManager;
use Modules\Ai\Filament\Pages\AiSettingsPage;
use Modules\Ai\Filament\Resources\McpClientResource;
use Modules\Ai\Http\Middleware\AuthenticateMcpClient;
use Modules\Ai\Filament\Resources\AgentChatResource;
use Modules\Ai\Http\Livewire\AgentChatComponent;
use Modules\Ai\Models\AgentChat;
use Modules\Ai\Policies\AgentChatPolicy;
use Modules\Ai\Services\AiService;
use Modules\Ai\Services\AiServiceImages;
use Modules\Ai\Services\Mcp\McpClientTokenManager;
use Modules\Ai\Services\Mcp\McpToolCatalog;
use Modules\Ai\Services\Secrets\PassCommandRunner;
use Modules\Ai\Services\Secrets\PassSecretStore;
use Modules\Ai\Services\Drivers\AiServiceInterface;

class AiServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Ai';

    protected string $moduleNameLower = 'ai';


    public function boot(): void
    {
        $this->setAiConfig();

        // Register the agent factory
        $this->app->singleton('ai.agents', function ($app) {
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
                config('modules.ai.default_driver', 'openai'),
                config('modules.ai.drivers', [])
            );
        });

        // Alias so AiService::class resolves to the 'ai' singleton
        $this->app->alias('ai', AiService::class);

        // Register the AI image service as a singleton
        $this->app->singleton('ai.images', function ($app) {
            return new AiServiceImages(
                config('modules.ai.default_driver_images'),
                config('modules.ai.drivers')
            );
        });

        // Alias so AiServiceImages::class resolves to the 'ai.images' singleton
        $this->app->alias('ai.images', AiServiceImages::class);
    }

    public function register(): void
    {
        $this->registerConfig();
        $this->registerViews();
        $this->app->singleton(McpClientTokenManager::class);
        $this->app->singleton(McpToolCatalog::class);
        $this->app->singleton(PassCommandRunner::class);
        $this->app->singleton(PassSecretStore::class);
        $this->app['router']->aliasMiddleware('mcp.client', AuthenticateMcpClient::class);
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Register policies
        Gate::policy(AgentChat::class, AgentChatPolicy::class);

        // Register Livewire components
        Livewire::component('ai.agent-chat-component', AgentChatComponent::class);

        FilamentRegistry::registerResource(AgentChatResource::class);
        FilamentRegistry::registerResource(McpClientResource::class);
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

            $enabled = get_option('enabled', 'ai');
            if ($enabled) {
                Config::set('modules.ai.enabled', $enabled);
            }


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

            $tavilyEnabled = get_option('tavily_enabled', 'ai');
            if ($tavilyEnabled !== null) {
                Config::set('modules.ai.drivers.tavily.enabled', (bool)$tavilyEnabled);
            }

            $supadataEnabled = get_option('supadata_enabled', 'ai');
            if ($supadataEnabled !== null) {
                Config::set('modules.ai.drivers.supadata.enabled', (bool)$supadataEnabled);
            }

            $anthropicEnabled = get_option('anthropic_enabled', 'ai');
            if ($anthropicEnabled !== null) {
                Config::set('modules.ai.drivers.anthropic.enabled', (bool)$anthropicEnabled);
            }

            $falEnabled = get_option('fal_enabled', 'ai');
            if ($falEnabled !== null) {
                Config::set('modules.ai.drivers.fal.enabled', (bool)$falEnabled);
            }

            // Load driver-specific settings
            $openAiModel = get_option('openai_model', 'ai');
            $openAiApiKey = $this->resolveAiSecretOption('openai_api_key');
            $openAiBaseUrl = get_option('openai_base_url', 'ai');
            $openRouterModel = get_option('openrouter_model', 'ai');
            $openRouterApiKey = $this->resolveAiSecretOption('openrouter_api_key');
            $ollamaModel = get_option('ollama_model', 'ai');
            $ollamaApiUrl = get_option('ollama_api_url', 'ai');
            $ollamaApiKey = $this->resolveAiSecretOption('ollama_api_key');
            $geminiModel = get_option('gemini_model', 'ai');
            $geminiApiKey = $this->resolveAiSecretOption('gemini_api_key');
            $anthropicModel = get_option('anthropic_model', 'ai');
            $anthropicApiKey = $this->resolveAiSecretOption('anthropic_api_key');
            $replicateApiToken = $this->resolveAiSecretOption('replicate_api_key');
            $replicateImageModel = get_option('replicate_model', 'ai');
            $tavilyApiKey = $this->resolveAiSecretOption('tavily_api_key');
            $tavilySearchDepth = get_option('tavily_search_depth', 'ai');
            $tavilyMaxResults = get_option('tavily_max_results', 'ai');
            $supadataApiKey = $this->resolveAiSecretOption('supadata_api_key');

            $falApiKey = $this->resolveAiSecretOption('fal_api_key');
            $falModel = get_option('fal_model', 'ai');

            if ($openAiModel) {
                Config::set('modules.ai.drivers.openai.model', $openAiModel);
            }
            if ($openAiApiKey) {
                Config::set('modules.ai.drivers.openai.api_key', $openAiApiKey);
            }
            if ($openAiBaseUrl) {
                Config::set('modules.ai.drivers.openai.base_url', $openAiBaseUrl);
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
            if ($ollamaApiKey) {
                Config::set('modules.ai.drivers.ollama.api_key', $ollamaApiKey);
            }

            if ($geminiModel) {
                Config::set('modules.ai.drivers.gemini.model', $geminiModel);
            }
            if ($geminiApiKey) {
                Config::set('modules.ai.drivers.gemini.api_key', $geminiApiKey);
            }

            if ($anthropicModel) {
                Config::set('modules.ai.drivers.anthropic.model', $anthropicModel);
            }
            if ($anthropicApiKey) {
                Config::set('modules.ai.drivers.anthropic.api_key', $anthropicApiKey);
            }

            if ($replicateApiToken) {
                Config::set('modules.ai.drivers.replicate.api_key', $replicateApiToken);
            }
            if ($replicateImageModel) {
                Config::set('modules.ai.drivers.replicate.model', $replicateImageModel);
            }

            if ($tavilyApiKey) {
                Config::set('modules.ai.drivers.tavily.api_key', $tavilyApiKey);
            }
            if ($tavilySearchDepth) {
                Config::set('modules.ai.drivers.tavily.search_depth', $tavilySearchDepth);
            }
            if ($tavilyMaxResults) {
                Config::set('modules.ai.drivers.tavily.max_results', (int)$tavilyMaxResults);
            }

            if ($supadataApiKey) {
                Config::set('modules.ai.drivers.supadata.api_key', $supadataApiKey);
            }

            if ($falApiKey) {
                Config::set('modules.ai.drivers.fal.api_key', $falApiKey);
            }
            if ($falModel) {
                Config::set('modules.ai.drivers.fal.model', $falModel);
            }
        }
    }

    private function resolveAiSecretOption(string $optionKey): ?string
    {
        $storedValue = get_option($optionKey, 'ai');

        if (blank($storedValue)) {
            return null;
        }

        /** @var PassSecretStore $secretStore */
        $secretStore = $this->app->make(PassSecretStore::class);

        return $secretStore->resolveAiProviderSecret(
            optionKey: $optionKey,
            storedValue: $storedValue,
            persistReference: function (string $reference) use ($optionKey): void {
                $this->persistAiSecretReference($optionKey, $reference);
            },
        );
    }

    private function persistAiSecretReference(string $optionKey, string $reference): void
    {
        save_option([
            'option_key' => $optionKey,
            'option_value' => $reference,
            'option_group' => 'ai',
            'module' => 'settings/group/website',
        ]);
    }
}
