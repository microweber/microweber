<?php

namespace Modules\WordPressMigration\Providers;

use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use Modules\WordPressMigration\Filament\Pages\WordPressMigrationImportPage;
use Modules\WordPressMigration\Filament\Pages\WordPressMigrationPreviewPage;
use Modules\WordPressMigration\Services\Http\CurlHttpProbeFetcher;
use Modules\WordPressMigration\Services\Http\HttpProbeFetcher;

class WordPressMigrationServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'WordPressMigration';

    protected string $moduleNameLower = 'wordpressmigration';

    public function register(): void
    {
        $this->registerConfig();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        $this->loadViewsFrom(
            module_path($this->moduleName, 'resources/views'),
            'microweber-module-wordpressmigration'
        );

        $this->app->bind(HttpProbeFetcher::class, CurlHttpProbeFetcher::class);

        FilamentRegistry::registerPage(WordPressMigrationImportPage::class);
        FilamentRegistry::registerPage(WordPressMigrationPreviewPage::class);
    }
}
