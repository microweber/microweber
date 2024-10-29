<?php

namespace Modules\Cloudflare\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use MicroweberPackages\LaravelModules\Providers\BaseModuleServiceProvider;
use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Microweber\Facades\Microweber;
use Modules\Cloudflare\Filament\CloudflareModuleSettings;
use Modules\Cloudflare\Helpers\CloudflareHelpers;
use Modules\Cloudflare\Microweber\CloudflareModule;

class CloudflareServiceProvider extends BaseModuleServiceProvider
{
    protected string $moduleName = 'Cloudflare';

    protected string $moduleNameLower = 'cloudflare';

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


        event_bind('mw.trust_proxies', function () {
            if (request()->header('cdn-loop') == 'cloudflare') {
                //check if the user has set in config
                $check = \Illuminate\Support\Facades\Config::get('trustedproxy.proxies');
                if ($check) {
                    return;
                }
                \Illuminate\Support\Facades\Config::set('trustedproxy.proxies', CloudflareHelpers::fetchCloudFlareIps());
            }
        });
    }

}
