<?php

namespace Templates\WebDesigner\Providers;

use MicroweberPackages\LaravelTemplates\Providers\BaseTemplateServiceProvider;

class WebDesignerTemplateServiceProvider extends BaseTemplateServiceProvider
{
    protected string $moduleName = 'WebDesigner';

    protected string $moduleNameLower = 'webdesigner';

    public function boot(): void
    {
    }

    public function register(): void
    {
        $this->registerConfig();
        $this->registerViews();
    }
}
