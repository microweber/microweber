<?php

namespace Modules\Settings\Filament\Pages;

use MicroweberPackages\Admin\Filament\Pages\Abstract\AdminSettingsPage;

class AdminFilesPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-files';

    protected static string $view = 'filament.admin.pages.settings-files';

    protected static ?string $title = 'Files';

    protected static string $description = 'Configure your file settings';
}
