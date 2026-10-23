<?php

namespace Modules\KitchenSink\Providers;

use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use Modules\KitchenSink\Filament\Pages\KitchenSink;
use Modules\KitchenSink\Filament\Pages\SamplePage;

class KitchenSinkServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'KitchenSink';

    protected string $moduleNameLower = 'kitchen_sink';

    public function register(): void
    {
        parent::register();

        $this->registerViews();

        // The kitchen-sink page: a single admin page that renders every Filament
        // component, used as a visual-regression reference while the theme CSS is
        // refactored (consolidation / !important removal).
        FilamentRegistry::registerPage(KitchenSink::class);

        // The sample page: the same components as the kitchen sink, but on ONE
        // flat page (no tabs). Reuses KitchenSink's form schema + the shared
        // view partials, so both stay in sync from one source.
        FilamentRegistry::registerPage(SamplePage::class);
    }
}
