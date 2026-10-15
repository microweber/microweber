<?php

namespace Templates\Hospital\Providers;

use MicroweberPackages\LaravelTemplates\Providers\BaseTemplateServiceProvider;

class HospitalTemplateServiceProvider extends BaseTemplateServiceProvider
{
    protected string $moduleName = 'Hospital';

    protected string $moduleNameLower = 'hospital';

    public function boot(): void
    {
    }

    public function register(): void
    {
        $this->registerConfig();
        $this->registerViews();
    }
}
