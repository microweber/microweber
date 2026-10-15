<?php

namespace Templates\Art\Providers;

use MicroweberPackages\LaravelTemplates\Providers\BaseTemplateServiceProvider;

class ArtTemplateServiceProvider extends BaseTemplateServiceProvider
{
    protected string $moduleName = 'Art';

    protected string $moduleNameLower = 'art';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->registerConfig();
        $this->registerViews();
    }
}
