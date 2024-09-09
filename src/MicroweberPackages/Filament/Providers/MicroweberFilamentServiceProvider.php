<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\Filament\Providers;


use MicroweberPackages\Admin\Providers\Filament\FilamentAdminPanelProvider;
use MicroweberPackages\Admin\Providers\Filament\FilamentLiveEditPanelProvider;
use MicroweberPackages\MicroweberFilamentTheme\MicroweberFilamentThemeServiceProvider;
use MicroweberPackages\Modules\Newsletter\Providers\NewsletterFilamentAdminPanelProvider;

class MicroweberFilamentServiceProvider extends \Illuminate\Support\ServiceProvider
{


    public function register()
    {
        //if (mw_is_installed()) {
        $this->app->register(FilamentServiceProvider::class);
        if (mw_is_installed()) {
            $this->app->register(FilamentAdminPanelProvider::class);
            $this->app->register(FilamentLiveEditPanelProvider::class);
            $this->app->register(MicroweberFilamentThemeServiceProvider::class);
        }

        //   }

    }
}
