<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Pages\Abstract\AdminSettingsPage;
use Filament\Pages\Page;

class AdminFilesPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-files';

    protected static string $view = 'filament.admin.pages.settings-files';

    protected static ?string $title = 'Files';

    protected static string $description = 'Configure your file settings';
}
