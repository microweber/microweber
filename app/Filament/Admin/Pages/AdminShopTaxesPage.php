<?php

namespace App\Filament\Admin\Pages;

use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class AdminShopTaxesPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-taxes';

    protected static string $view = 'filament.admin.pages.settings-shop-taxes';

    protected static ?string $title = 'Taxes';

    protected static string $description = 'Configure your shop taxes settings';

    protected static ?string $navigationGroup = 'Shop Settings';


}

