<?php

namespace App\Filament\Admin\Pages;

use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class AdminLanguagePage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-language';

    protected static string $view = 'filament.admin.pages.settings-language';

    protected static ?string $title = 'Language';

    protected static string $description = 'Configure your language settings';

}
