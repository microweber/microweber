<?php

namespace Modules\Settings\Filament\Pages;

use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class AdminUpdatesPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-updates';

    protected static string $view = 'modules.settings::filament.admin.pages.settings-form';

    protected static ?string $title = 'Updates';

    protected static string $description = 'Check for the latest updates';
}
