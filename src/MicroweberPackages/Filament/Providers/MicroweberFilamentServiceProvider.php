<?php
/*
* This file is part of the Microweber framework.
*
* (c) Microweber CMS LTD
*
* For full license information see
* https://github.com/microweber/microweber/blob/master/LICENSE
*/

namespace MicroweberPackages\Filament\Providers;


use MicroweberPackages\MicroweberFilamentTheme\MicroweberFilamentThemeServiceProvider;
use MicroweberPackages\Admin\Filament\FilamentAdminPanelProvider;

class MicroweberFilamentServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        // Register core Filament v5 panel providers (no deprecated FilamentServiceProvider)
        $this->app->register(MicroweberFilamentThemeServiceProvider::class);
        $this->app->register(FilamentAdminPanelProvider::class);
    }
}
