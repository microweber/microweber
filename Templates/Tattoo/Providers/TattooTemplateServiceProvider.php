<?php

namespace Templates\Tattoo\Providers;

use MicroweberPackages\LaravelTemplates\Providers\BaseTemplateServiceProvider;

class TattooTemplateServiceProvider extends BaseTemplateServiceProvider
{
    protected string $moduleName = 'Tattoo';

    protected string $moduleNameLower = 'tattoo';

    public function boot(): void
    {
    }

    public function register(): void
    {
        $this->registerConfig();
        $this->registerViews();
    }
}
