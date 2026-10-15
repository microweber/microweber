<?php

namespace Templates\NotaryServices\Providers;

use MicroweberPackages\LaravelTemplates\Providers\BaseTemplateServiceProvider;

class NotaryServicesTemplateServiceProvider extends BaseTemplateServiceProvider
{
    protected string $moduleName = 'NotaryServices';

    protected string $moduleNameLower = 'notaryservices';

    public function boot(): void
    {
    }

    public function register(): void
    {
        $this->registerConfig();
        $this->registerViews();
    }
}
