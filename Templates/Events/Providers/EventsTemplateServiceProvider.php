<?php

namespace Templates\Events\Providers;

use MicroweberPackages\LaravelTemplates\Providers\BaseTemplateServiceProvider;

class EventsTemplateServiceProvider extends BaseTemplateServiceProvider
{
    protected string $moduleName = 'Events';

    protected string $moduleNameLower = 'events';

    public function boot(): void
    {
    }

    public function register(): void
    {
        $this->registerConfig();
        $this->registerViews();
    }
}
