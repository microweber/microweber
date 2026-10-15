<?php

namespace Templates\Barbershop\Providers;

use MicroweberPackages\LaravelTemplates\Providers\BaseTemplateServiceProvider;

class BarbershopTemplateServiceProvider extends BaseTemplateServiceProvider
{
    protected string $moduleName = 'Barbershop';

    protected string $moduleNameLower = 'barbershop';

    public function boot(): void
    {
    }

    public function register(): void
    {
        $this->registerConfig();
        $this->registerViews();
    }
}
