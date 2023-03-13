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


class FilamentPackageServiceProvider extends \Filament\FilamentServiceProvider
{
    public function packageRegistered(): void
    {
        parent::packageRegistered();

        /** @var \Filament\FilamentManager $filament */

        $this->app->scoped('filament', function (): FilamentManager {
            return new FilamentManager();
        });
    }

}
