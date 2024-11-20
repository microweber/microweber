<?php

namespace Modules\Settings\Filament\Pages;

use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class AdminShopShippingPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-shipping';

    protected static string $view = 'modules.settings::filament.admin.pages.settings-form';

    protected static ?string $title = 'Shipping';

    protected static string $description = 'Configure your shop shipping settings';

    protected static ?string $navigationGroup = 'Shop Settings';


}
