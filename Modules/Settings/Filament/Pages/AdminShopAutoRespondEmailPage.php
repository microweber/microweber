<?php

namespace Modules\Settings\Filament\Pages;

use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class AdminShopAutoRespondEmailPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-autorespondEmail';

    protected static string $view = 'modules.settings::filament.admin.pages.settings-form';

    protected static ?string $title = 'Auto Respond Email';

    protected static string $description = 'Configure your shop auto respond email settings';

    protected static ?string $navigationGroup = 'Shop Settings';

}
