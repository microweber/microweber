<?php

namespace Templates\Freelancer\Providers;

use MicroweberPackages\LaravelTemplates\Providers\BaseTemplateServiceProvider;

class FreelancerTemplateServiceProvider extends BaseTemplateServiceProvider
{
    protected string $moduleName = 'Freelancer';

    protected string $moduleNameLower = 'freelancer';

    public function boot(): void
    {
    }

    public function register(): void
    {
        $this->registerConfig();
        $this->registerViews();
    }
}
