<?php

namespace Modules\FacebookPage\Providers;

use Illuminate\Support\ServiceProvider;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\FacebookPage\Filament\FacebookPageModuleSettings;

class FacebookPageServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'FacebookPage';

    protected string $moduleNameLower = 'facebook_page';



    public function register(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        FilamentRegistry::registerPage(FacebookPageModuleSettings::class);
        Microweber::module(\Modules\FacebookPage\Microweber\FacebookPageModule::class);
    }
}
