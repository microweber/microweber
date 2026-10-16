<?php

namespace Modules\DigitalProduct\Providers;

use MicroweberPackages\FilamentRegistry\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use Modules\DigitalProduct\Filament\Resources\ProductDownloadableFileResource;
use Modules\DigitalProduct\Services\DigitalProductService;

class DigitalProductServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'DigitalProduct';

    protected string $moduleNameLower = 'digitalproduct';

    public function register(): void
    {
        parent::register();

        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));

        $this->app->singleton('digital_product_service', function () {
            return new DigitalProductService();
        });

        // Grant downloads when an order is paid.
        $this->app->register(DigitalProductEventServiceProvider::class);

        FilamentRegistry::registerResource(ProductDownloadableFileResource::class);

        // Customer "My Downloads" page in the profile panel (the panel consumes
        // FilamentRegistry::getPages('profile', 'profile')).
        FilamentRegistry::registerPage(
            \Modules\DigitalProduct\Filament\Pages\MyDownloads::class,
            'profile',
            'profile'
        );

        FilamentRegistry::registerGlobalSearchEntry(
            'Downloadable files', '/admin/product-downloadable-files',
            ['digital', 'download', 'downloadable', 'digital product', 'file', 'ebook'],
            'Shop', ['Section' => 'Shop'],
        );
    }

    public function boot(): void
    {
    }
}
