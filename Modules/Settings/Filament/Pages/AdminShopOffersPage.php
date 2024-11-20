<?php

namespace Modules\Settings\Filament\Pages;

use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class AdminShopOffersPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-offers';

    protected static string $view = 'modules.settings::filament.admin.pages.settings-form';

    protected static ?string $title = 'Offers';

    protected static string $description = 'Configure your shop offers settings';

    protected static ?string $navigationGroup = 'Shop Settings';


}
