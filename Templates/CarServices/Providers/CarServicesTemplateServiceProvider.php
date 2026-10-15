<?php

namespace Templates\CarServices\Providers;

use MicroweberPackages\LaravelTemplates\Providers\BaseTemplateServiceProvider;

class CarServicesTemplateServiceProvider extends BaseTemplateServiceProvider
{
    protected string $moduleName = 'CarServices';

    protected string $moduleNameLower = 'carservices';

    public function boot(): void
    {
    }

    public function register(): void
    {
        $this->registerConfig();
        $this->registerViews();
    }
}
