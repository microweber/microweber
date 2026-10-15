<?php

namespace Templates\Simple\Providers;

use MicroweberPackages\LaravelTemplates\Providers\BaseTemplateServiceProvider;

class SimpleTemplateServiceProvider extends BaseTemplateServiceProvider
{
    protected string $moduleName = 'Simple';

    protected string $moduleNameLower = 'simple';

    public function boot(): void
    {
    }

    public function register(): void
    {
        $this->registerConfig();
        $this->registerViews();
    }
}
