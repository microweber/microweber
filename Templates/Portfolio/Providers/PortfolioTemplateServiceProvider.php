<?php

namespace Templates\Portfolio\Providers;

use MicroweberPackages\LaravelTemplates\Providers\BaseTemplateServiceProvider;

class PortfolioTemplateServiceProvider extends BaseTemplateServiceProvider
{
    protected string $moduleName = 'Portfolio';

    protected string $moduleNameLower = 'portfolio';

    public function boot(): void
    {
    }

    public function register(): void
    {
        $this->registerConfig();
        $this->registerViews();
    }
}
