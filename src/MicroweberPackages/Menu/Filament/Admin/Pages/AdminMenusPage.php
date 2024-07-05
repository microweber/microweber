<?php

namespace MicroweberPackages\Menu\Filament\Admin\Pages;

use App\Filament\Admin\Pages\Abstract\AdminSettingsPage;


class AdminMenusPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-menu';

    protected static ?string $navigationGroup = 'Website Settings';

    protected static string $view = 'menu::filament.admin.pages.menus-list-page';

    protected static ?string $title = 'Menu';

    protected static string $description = 'Configure your menus';

    protected static ?string $slug = 'settings/menus';

}
