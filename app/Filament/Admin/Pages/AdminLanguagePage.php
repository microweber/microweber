<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Pages\Abstract\AdminSettingsPage;
use Filament\Pages\Page;

class AdminLanguagePage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-language';

    protected static string $view = 'filament.admin.pages.settings-language';

    protected static ?string $title = 'Language';

    protected static string $description = 'Configure your language settings';

}
