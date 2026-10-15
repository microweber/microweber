<?php

namespace Templates\Office\Providers;

use MicroweberPackages\LaravelTemplates\Providers\BaseTemplateServiceProvider;

class OfficeTemplateServiceProvider extends BaseTemplateServiceProvider
{
    protected string $moduleName = 'Office';

    protected string $moduleNameLower = 'office';

    public function boot(): void
    {
    }

    public function register(): void
    {
        $this->registerConfig();
        $this->registerViews();
    }
}
