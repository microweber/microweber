<?php

namespace MicroweberPackages\Admin\Providers\Filament;

use MicroweberPackages\Modules\Logo\Http\Livewire\LogoSettings;

class FilamentLiveEditPanelProvider extends FilamentAdminPanelProvider
{
    public string $filamentId = 'admin-live-edit';
    public string $filamentPath = 'admin-live-edit';

    public function getPanelPages(): array
    {
        return [
            //  Pages\Dashboard::class,
            LogoSettings::class,
        ];
    }

    public function getPanelMiddlewares(): array
    {

        $defaultMiddlewares = parent::getPanelMiddlewares();
        $defaultMiddlewares[] = 'module_settings';

        return $defaultMiddlewares;
    }

}
