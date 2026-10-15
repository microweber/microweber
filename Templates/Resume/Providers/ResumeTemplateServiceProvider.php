<?php

namespace Templates\Resume\Providers;

use MicroweberPackages\LaravelTemplates\Providers\BaseTemplateServiceProvider;

class ResumeTemplateServiceProvider extends BaseTemplateServiceProvider
{
    protected string $moduleName = 'Resume';

    protected string $moduleNameLower = 'resume';

    public function boot(): void
    {
    }

    public function register(): void
    {
        $this->registerConfig();
        $this->registerViews();
    }
}
