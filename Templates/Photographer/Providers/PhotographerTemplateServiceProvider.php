<?php

namespace Templates\Photographer\Providers;

use MicroweberPackages\LaravelTemplates\Providers\BaseTemplateServiceProvider;

class PhotographerTemplateServiceProvider extends BaseTemplateServiceProvider
{
    protected string $moduleName = 'Photographer';

    protected string $moduleNameLower = 'photographer';

    public function boot(): void
    {
    }

    public function register(): void
    {
        $this->registerConfig();
        $this->registerViews();
    }
}
