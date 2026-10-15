<?php

namespace Templates\Burger\Providers;

use MicroweberPackages\LaravelTemplates\Providers\BaseTemplateServiceProvider;

class BurgerTemplateServiceProvider extends BaseTemplateServiceProvider
{
    protected string $moduleName = 'Burger';

    protected string $moduleNameLower = 'burger';

    public function boot(): void
    {
    }

    public function register(): void
    {
        $this->registerConfig();
        $this->registerViews();
    }
}
