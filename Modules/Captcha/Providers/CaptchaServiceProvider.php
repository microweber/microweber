<?php

namespace Modules\Captcha\Providers;

use Illuminate\Support\Facades\Validator;
use Livewire\Livewire;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Captcha\Livewire\CaptchaConfirmModalComponent;
use Modules\Captcha\Microweber\CaptchaModule;
use Modules\Captcha\Services\CaptchaManager;
use Modules\Captcha\Validators\CaptchaValidator;

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
        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/api.php'));
        Livewire::component('captcha-confirm-modal', CaptchaConfirmModalComponent::class);

        // Register Microweber module
        Microweber::module(CaptchaModule::class);

        /**
         * @property \Modules\Captcha\Services\CaptchaManager $captcha_manager
         */
        $this->app->singleton('captcha_manager', function ($app) {
            return new CaptchaManager();
        });

        Validator::extendImplicit('captcha', CaptchaValidator::class.'@validate', 'Invalid captcha answer!');


    }

}
