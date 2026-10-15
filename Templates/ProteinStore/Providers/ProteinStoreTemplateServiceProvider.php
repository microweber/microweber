<?php

namespace Templates\ProteinStore\Providers;

use MicroweberPackages\LaravelTemplates\Providers\BaseTemplateServiceProvider;

class ProteinStoreTemplateServiceProvider extends BaseTemplateServiceProvider
{
    protected string $moduleName = 'ProteinStore';

    protected string $moduleNameLower = 'proteinstore';

    public function boot(): void
    {
    }

    public function register(): void
    {
        $this->registerConfig();
        $this->registerViews();
    }
}
