<?php

namespace MicroweberPackages\Modules\Menu\Providers;

use Filament\Events\ServingFilament;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use MicroweberPackages\Module\Facades\ModuleAdmin;
use MicroweberPackages\Modules\Menu\Filament\Admin\MenuFilamentPlugin;
use MicroweberPackages\Modules\Menu\Filament\Admin\Pages\AdminMenusPage;


class MenuModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        ModuleAdmin::registerPanelPlugin(MenuFilamentPlugin::class);

        Event::listen(ServingFilament::class, function () {


            ModuleAdmin::registerPanelPage(AdminMenusPage::class, 'settings');
        });

    }
}
