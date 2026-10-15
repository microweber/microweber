<?php

namespace Templates\Beauty\Providers;

use MicroweberPackages\LaravelTemplates\Providers\BaseTemplateServiceProvider;

class BeautyTemplateServiceProvider extends BaseTemplateServiceProvider
{
    protected string $moduleName = 'Beauty';

    protected string $moduleNameLower = 'beauty';

    public function boot(): void
    {
    }

    public function register(): void
    {
        $this->registerConfig();
        $this->registerViews();
    }
}
