<?php

namespace Templates\Conference\Providers;

use MicroweberPackages\LaravelTemplates\Providers\BaseTemplateServiceProvider;

class ConferenceTemplateServiceProvider extends BaseTemplateServiceProvider
{
    protected string $moduleName = 'Conference';

    protected string $moduleNameLower = 'conference';

    public function boot(): void
    {
    }

    public function register(): void
    {
        $this->registerConfig();
        $this->registerViews();
    }
}
