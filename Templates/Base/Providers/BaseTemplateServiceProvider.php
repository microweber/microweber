<?php

namespace Templates\Base\Providers;

use MicroweberPackages\LaravelTemplates\Providers\BaseTemplateServiceProvider as BaseServiceProvider;

class BaseTemplateServiceProvider extends BaseServiceProvider
{
    protected string $moduleName = 'Base';

    protected string $moduleNameLower = 'base';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        // Register this template's design-system adapter (decoupled from the core
        // DesignSystemService — each template registers its own from its provider).
        if (app()->bound('design_system')) {
            app('design_system')->registerAdapter(
                new \MicroweberPackages\Template\Services\DesignSystem\Adapters\BaseTemplateVarsAdapter()
            );
        }
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->registerConfig();
        $this->registerViews();
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }
}