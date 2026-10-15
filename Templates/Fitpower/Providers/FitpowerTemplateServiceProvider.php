<?php

namespace Templates\Fitpower\Providers;

use MicroweberPackages\LaravelTemplates\Providers\BaseTemplateServiceProvider;

class FitpowerTemplateServiceProvider extends BaseTemplateServiceProvider
{
    protected string $moduleName = 'Fitpower';

    protected string $moduleNameLower = 'fitpower';

    public function boot(): void
    {
    }

    public function register(): void
    {
        $this->registerConfig();
        $this->registerViews();
    }
}
