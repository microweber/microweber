<?php

namespace MicroweberPackages\Admin\Providers\Filament;

use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Modules\Logo\Http\Livewire\LogoModuleSettings;
use MicroweberPackages\Modules\Shop\Http\Livewire\ShopModuleSettings;

class FilamentLiveEditPanelProvider extends FilamentAdminPanelProvider
{
    public string $filamentId = 'admin-live-edit';
    public string $filamentPath = 'admin-live-edit';

    public function __construct($app)
    {
        parent::__construct($app);
        $this->filamentPath = mw_admin_prefix_url_live_edit();
    }
    public function getPanelPages(): array
    {
        // return ModuleAdmin::getPanelPages();
        return ModuleAdmin::getLiveEditPanelPages();

//        return [
//            //  Pages\Dashboard::class,
//            \MicroweberPackages\Modules\Logo\Http\Livewire\LogoSettings::class,
//            \MicroweberPackages\Modules\Logo\Http\Livewire\ShopSettings::class,
//         ];
    }

    public function getPanelMiddlewares(): array
    {

        $defaultMiddlewares = parent::getPanelMiddlewares();
        $defaultMiddlewares[] = 'module_settings';

        return $defaultMiddlewares;
    }

}