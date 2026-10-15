<?php

namespace Templates\Yummy\Providers;

use MicroweberPackages\LaravelTemplates\Providers\BaseTemplateServiceProvider;

class YummyTemplateServiceProvider extends BaseTemplateServiceProvider
{
    protected string $moduleName = 'Yummy';

    protected string $moduleNameLower = 'yummy';

    public function boot(): void
    {
    }

    public function register(): void
    {
        $this->registerConfig();
        $this->registerViews();
    }
}
