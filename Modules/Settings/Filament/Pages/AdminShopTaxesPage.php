<?php

namespace Modules\Settings\Filament\Pages;

use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class AdminShopTaxesPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-taxes';

    protected static string $view = 'modules.settings::filament.admin.pages.settings-form';

    protected static ?string $title = 'Taxes';

    protected static string $description = 'Configure your shop taxes settings';

    protected static ?string $navigationGroup = 'Shop Settings';


}

