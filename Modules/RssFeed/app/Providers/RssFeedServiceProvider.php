<?php

namespace Modules\RssFeed\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Package\ModulePackage;

class RssFeedServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'RssFeed';

    protected string $moduleNameLower = 'rssfeed';

    /**
     * Boot the application events.
     */
    public function register(): void
    {

        $this->registerConfig();
        $this->registerViews();
        $this->registerTranslations();
        Route::middleware('web')->group(module_path('RssFeed', '/routes/web.php'));

    }


}
