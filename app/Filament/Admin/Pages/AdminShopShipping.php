<?php

namespace App\Filament\Admin\Pages;

use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class AdminShopShipping extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-shipping';

    protected static string $view = 'filament.admin.pages.settings-shop-shipping';

    protected static ?string $title = 'Shipping';

    protected static string $description = 'Configure your shop shipping settings';

    protected static ?string $navigationGroup = 'Shop Settings';


}
