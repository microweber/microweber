<?php

namespace Templates\HomeRestoration\Providers;

use MicroweberPackages\LaravelTemplates\Providers\BaseTemplateServiceProvider;

class HomeRestorationTemplateServiceProvider extends BaseTemplateServiceProvider
{
    protected string $moduleName = 'HomeRestoration';

    protected string $moduleNameLower = 'homerestoration';

    public function boot(): void
    {
    }

    public function register(): void
    {
        $this->registerConfig();
        $this->registerViews();
    }
}
