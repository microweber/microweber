<?php

namespace Modules\Menu\Filament\Admin\Pages;

use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;


class AdminMenusPage extends AdminSettingsPage
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-bars-3';

    protected static string | \UnitEnum | null $navigationGroup = 'Website Settings';

    protected string $view = 'modules.menu::filament.admin.pages.menus-list-page';

    protected static ?string $title = 'Menu';

    protected static string $description = 'Configure your menus';

    protected static ?string $slug = 'settings/menus';
    protected static bool $shouldRegisterNavigation = true;

}
