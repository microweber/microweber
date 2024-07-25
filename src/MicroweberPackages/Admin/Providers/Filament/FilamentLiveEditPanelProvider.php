<?php

namespace MicroweberPackages\Admin\Providers\Filament;

use Filament\Panel;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\AdminLiveEditPage;
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

    public function panel(Panel $panel): Panel
    {
        $panel = parent::panel($panel);
        $panel->navigation(false);
        $panel->topbar(false);
        return $panel;

    }
    public function getPanelResources(): array
    {
        return [];
    }
//    public function getPanelPages(): array
//    {
//        dd(33);
//        // return ModuleAdmin::getPanelPages();
//        $page_default = [
//            AdminLiveEditPage::class,
//        ];
//        $pages = ModuleAdmin::getLiveEditPanelPages();
//
//        return array_merge($page_default, $pages);
//
//
//        // return ModuleAdmin::getLiveEditPanelPages();
//
////        return [
////            //  Pages\Dashboard::class,
////            \MicroweberPackages\Modules\Logo\Http\Livewire\LogoSettings::class,
////            \MicroweberPackages\Modules\Logo\Http\Livewire\ShopSettings::class,
////         ];
//    }

    public function getPanelMiddlewares(): array
    {

        $defaultMiddlewares = parent::getPanelMiddlewares();
        $defaultMiddlewares[] = 'module_settings';

        return $defaultMiddlewares;
    }

}
