<?php

namespace Modules\Captcha\Providers;

use Livewire\Livewire;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Captcha\Livewire\CaptchaConfirmModalComponent;
use Modules\Captcha\Microweber\CaptchaModule;

class CaptchaServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Captcha';

    protected string $moduleNameLower = 'captcha';

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
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        //$this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/web.php'));
        Livewire::component('captcha-confirm-modal', CaptchaConfirmModalComponent::class);

        // Register Microweber module
        Microweber::module(CaptchaModule::class);

    }

}
