<?php

namespace Templates\Nomad\Providers;

use MicroweberPackages\LaravelTemplates\Providers\BaseTemplateServiceProvider;

class NomadTemplateServiceProvider extends BaseTemplateServiceProvider
{
    protected string $moduleName = 'Nomad';

    protected string $moduleNameLower = 'nomad';

    public function boot(): void
    {
    }

    public function register(): void
    {
        $this->registerConfig();
        $this->registerViews();
    }
}
