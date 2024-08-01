<?php

namespace App\Filament\Admin\Pages;

use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class AdminUpdatesPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-updates';

    protected static string $view = 'filament.admin.pages.settings-updates';

    protected static ?string $title = 'Updates';

    protected static string $description = 'Check for the latest updates';
}
