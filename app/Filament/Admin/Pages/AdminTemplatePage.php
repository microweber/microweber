<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Pages\Abstract\AdminSettingsPage;
use Filament\Pages\Page;

class AdminTemplatePage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-template';

    protected static string $view = 'filament.admin.pages.settings-template';

    protected static ?string $title = 'Template';

    protected static string $description = 'Configure your template settings';


}
