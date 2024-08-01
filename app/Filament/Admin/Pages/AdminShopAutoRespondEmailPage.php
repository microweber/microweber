<?php

namespace App\Filament\Admin\Pages;

use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class AdminShopAutoRespondEmailPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-autorespondEmail';

    protected static string $view = 'filament.admin.pages.settings-shop-auto-respond-email';

    protected static ?string $title = 'Auto Respond Email';

    protected static string $description = 'Configure your shop auto respond email settings';

    protected static ?string $navigationGroup = 'Shop Settings';

}
