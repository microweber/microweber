<?php

namespace Modules\WordPressMigration\Providers;

use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use Modules\WordPressMigration\Console\Commands\ImportWordPressCommand;
use Modules\WordPressMigration\Console\Commands\ImportWordPressCommitCommand;
use Modules\WordPressMigration\Console\Commands\ImportWordPressStatusCommand;
use Modules\WordPressMigration\Filament\Pages\WordPressMigrationImportPage;
use Modules\WordPressMigration\Filament\Pages\WordPressMigrationPreviewPage;
use Modules\WordPressMigration\Filament\Resources\WordPressMigrationResource;
use Modules\WordPressMigration\Filament\Widgets\WordPressImportCtaWidget;
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
        FilamentRegistry::registerResource(WordPressMigrationResource::class);

        FilamentRegistry::registerWidget(
            WordPressImportCtaWidget::class,
            \App\Filament\Admin\Pages\Dashboard::class,
        );

        if ($this->app->runningInConsole()) {
            $this->commands([
                ImportWordPressCommand::class,
                ImportWordPressStatusCommand::class,
                ImportWordPressCommitCommand::class,
            ]);
        }
    }
}
