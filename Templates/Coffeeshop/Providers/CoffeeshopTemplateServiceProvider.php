<?php

namespace Templates\Coffeeshop\Providers;

use MicroweberPackages\LaravelTemplates\Providers\BaseTemplateServiceProvider;

class CoffeeshopTemplateServiceProvider extends BaseTemplateServiceProvider
{
    protected string $moduleName = 'Coffeeshop';

    protected string $moduleNameLower = 'coffeeshop';

    public function boot(): void
    {
    }

    public function register(): void
    {
        $this->registerConfig();
        $this->registerViews();
    }
}
