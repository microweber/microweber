<?php

namespace Templates\Yoga\Providers;

use MicroweberPackages\LaravelTemplates\Providers\BaseTemplateServiceProvider;

class YogaTemplateServiceProvider extends BaseTemplateServiceProvider
{
    protected string $moduleName = 'Yoga';

    protected string $moduleNameLower = 'yoga';

    public function boot(): void
    {
    }

    public function register(): void
    {
        $this->registerConfig();
        $this->registerViews();
    }
}
