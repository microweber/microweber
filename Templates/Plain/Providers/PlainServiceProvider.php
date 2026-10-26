<?php

namespace Templates\Plain\Providers;

use MicroweberPackages\LaravelTemplates\Providers\BaseTemplateServiceProvider;

/**
 * Service provider for the minimal "Plain" template. Registers the
 * templates.plain:: view namespace + config. Deliberately registers NO
 * design-system adapter: Plain ships no design tokens — all visual design comes
 * from the AI agent's global apply_css and Live-Edit edits.
 */
class PlainServiceProvider extends BaseTemplateServiceProvider
{
    protected string $moduleName = 'Plain';

    protected string $moduleNameLower = 'plain';

    public function boot(): void
    {
    }

    public function register(): void
    {
        parent::register();

        $this->registerConfig();
        $this->registerViews();
    }
}
