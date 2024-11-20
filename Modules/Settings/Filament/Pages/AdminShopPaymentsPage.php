<?php

namespace Modules\Settings\Filament\Pages;

use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class AdminShopPaymentsPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-payments';

    protected static string $view = 'modules.settings::filament.admin.pages.settings-form';

    protected static ?string $title = 'Payments';

    protected static string $description = 'Configure your shop payments settings';

    protected static ?string $navigationGroup = 'Shop Settings';


}
