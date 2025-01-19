<?php

namespace Modules\CookieNotice\Providers;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\CookieNotice\Filament\Pages\CookieNoticeModuleSettingsAdmin;
use Modules\CookieNotice\Microweber\CookieNoticeModule;

class CookieNoticeServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'CookieNotice';
    protected string $moduleNameLower = 'cookie_notice';


    public function register(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'database/migrations'));


        $this->loadRoutesFrom(module_path($this->moduleName, 'routes/api.php'));


        // Register filament page for Microweber module settings
        FilamentRegistry::registerPage(CookieNoticeModuleSettingsAdmin::class);
        // Register Microweber module
        Microweber::module(CookieNoticeModule::class);
        $cookieName = config('modules.cookie_notice.cookie_name') ?? 'cookie_notice_accepted';
        $hasCookie = Cookie::get($cookieName);
        if (!$hasCookie) {
            $isEnabled = get_option('enable_cookie_notice', 'cookie_notice');

            if ($isEnabled) {
                template_foot(function () {
                    //add the css file
                    $assetUrl = asset('modules/cookie_notice/css/cookie-notice.css');
                    $str = '<link rel="stylesheet" href="' . $assetUrl . '" type="text/css"  id="cookie-notice-css" />';
                    return $str;
                });
                template_foot(function () {

                    return '<module type="cookie_notice" id="cookie_notice_module" />';
                });
            }
        }

    }


}
