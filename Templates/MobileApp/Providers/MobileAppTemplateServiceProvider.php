<?php

namespace Templates\MobileApp\Providers;

use MicroweberPackages\LaravelTemplates\Providers\BaseTemplateServiceProvider;

class MobileAppTemplateServiceProvider extends BaseTemplateServiceProvider
{
    protected string $moduleName = 'MobileApp';

    protected string $moduleNameLower = 'mobileapp';

    public function boot(): void
    {
    }

    public function register(): void
    {
        $this->registerConfig();
        $this->registerViews();
    }
}
