<?php

namespace Modules\RssFeed\Providers;

use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;

class RssFeedServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'RssFeed';

    protected string $moduleNameLower = 'rssfeed';

    /**
     * Boot the application events.
     */
    public function register(): void
    {
        parent::register();


        $this->registerConfig();
        $this->registerViews();
        $this->registerTranslations();
        Route::middleware('web')->group(module_path('RssFeed', '/routes/web.php'));

    }


}
