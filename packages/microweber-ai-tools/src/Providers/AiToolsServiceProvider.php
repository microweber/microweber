<?php

declare(strict_types=1);

namespace MicroweberPackages\AiTools\Providers;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\AiTools\Contracts\ToolRegistryInterface;
use MicroweberPackages\AiTools\Registry\ToolRegistry;

/**
 * Standalone service provider for the AI Tools package.
 *
 * No CMS-specific dependencies — works in any Laravel application.
 * Domain tools are expected to register themselves via the ToolRegistry
 * (from modules, app service providers, or config/ai-tools.php).
 */
class AiToolsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/ai-tools.php',
            'ai-tools'
        );

        // One singleton for the registry; the interface + string name are aliases
        // to the same instance (so interface, concrete and 'aitools.registry' all
        // resolve to it). The AiTools facade is used via its class — no global
        // class-alias needed.
        $this->app->singleton(ToolRegistry::class, static function (): ToolRegistry {
            return new ToolRegistry();
        });
        $this->app->alias(ToolRegistry::class, ToolRegistryInterface::class);
        $this->app->alias(ToolRegistry::class, 'aitools.registry');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/ai-tools.php' => config_path('ai-tools.php'),
            ], 'ai-tools-config');
        }

        $this->registerConfiguredTools();
    }

    /**
     * Register tools listed in config('ai-tools.tools').
     */
    protected function registerConfiguredTools(): void
    {
        if (!config('ai-tools.enabled', true)) {
            return;
        }

        /** @var ToolRegistryInterface $registry */
        $registry = $this->app->make(ToolRegistryInterface::class);
        /** @var list<mixed> $tools */
        $tools = config('ai-tools.tools', []);

        foreach ($tools as $toolClass) {
            if (
                is_string($toolClass)
                && class_exists($toolClass)
                && is_subclass_of($toolClass, \MicroweberPackages\AiTools\Contracts\ToolInterface::class)
            ) {
                /** @var class-string<\MicroweberPackages\AiTools\Contracts\ToolInterface> $toolClass */
                $registry->register($toolClass);
            }
        }
    }
}
