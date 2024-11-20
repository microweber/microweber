<?php

namespace Modules\Settings\Filament\Pages;

use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class AdminShopOtherPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-general';

    protected static string $view = 'modules.settings::filament.admin.pages.settings-form';

    protected static ?string $title = 'Other';

    protected static string $description = 'Configure your shop other settings';

    protected static ?string $navigationGroup = 'Shop Settings';


}
