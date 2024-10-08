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


use MicroweberPackages\Filament\Facades\FilamentRegistry;
use MicroweberPackages\Filament\FilamentRegistryManager;

class MicroweberFilamentRegistryServiceProvider extends \Illuminate\Support\ServiceProvider
{


    public function register()
    {
        if (mw_is_installed()) {


            $this->app->singleton(FilamentRegistry::class, function () {
                return new FilamentRegistryManager();
            });
        }

    }
}
