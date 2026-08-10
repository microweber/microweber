<?php

namespace Modules\FileManager\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\FileManager\Filament\Pages\FileManagerPageAdmin;
use Modules\Settings\Filament\Pages\Settings;


class FileManagerServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'FileManager';

    protected string $moduleNameLower = 'file-manager';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {


    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        parent::register();

        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));

        FilamentRegistry::registerPage(FileManagerPageAdmin::class);
        FilamentRegistry::registerPage(FileManagerPageAdmin::class,Settings::class);

        FilamentRegistry::registerGlobalSearchEntry(
            'File Manager', '/admin/settings/file-manager',
            ['file manager', 'files', 'file upload', 'file browser',
             'documents', 'download'],
            'Admin Pages', ['Section' => 'Website'],
        );

        // Register Microweber module
        // Microweber::module(\Modules\FileManager\Microweber\FileManagerModule::class);

    }

}
