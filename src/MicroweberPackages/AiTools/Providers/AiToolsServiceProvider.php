<?php

declare(strict_types=1);

namespace MicroweberPackages\AiTools\Providers;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\AiTools\Contracts\ToolRegistryInterface;
use MicroweberPackages\AiTools\Registry\ToolRegistry;

/**
 * Service Provider for AI Tools package.
 *
 * Registers the tool registry and provides configuration
 * for the AI Tools ecosystem.
 */
class AiToolsServiceProvider extends ServiceProvider
{
    use \MicroweberPackages\ConfigMerge\MergesConfigFromPackage;
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        // Register the tool registry as singleton
        $this->app->singleton(ToolRegistryInterface::class, function () {
            return new ToolRegistry();
        });

        // Alias for backward compatibility
        $this->app->alias(ToolRegistryInterface::class, 'aitools.registry');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Publish configuration
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/ai-tools.php' => config_path('ai-tools.php'),
            ], 'ai-tools-config');
        }

        // Merge configuration
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/ai-tools.php',
            'ai-tools'
        );

        // Auto-register tools from config
        $this->registerConfiguredTools();
    }

    /**
     * Register tools from configuration.
     *
     * @return void
     */
    protected function registerConfiguredTools(): void
    {
        $registry = $this->app->make(ToolRegistryInterface::class);
        $tools = config('ai-tools.tools', []);

        foreach ($tools as $toolClass) {
            if (class_exists($toolClass)) {
                $registry->register($toolClass);
            }
        }
    }
}
