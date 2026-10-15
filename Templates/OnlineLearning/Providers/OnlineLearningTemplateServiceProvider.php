<?php

namespace Templates\OnlineLearning\Providers;

use MicroweberPackages\LaravelTemplates\Providers\BaseTemplateServiceProvider;

class OnlineLearningTemplateServiceProvider extends BaseTemplateServiceProvider
{
    protected string $moduleName = 'OnlineLearning';

    protected string $moduleNameLower = 'onlinelearning';

    public function boot(): void
    {
    }

    public function register(): void
    {
        $this->registerConfig();
        $this->registerViews();
    }
}
