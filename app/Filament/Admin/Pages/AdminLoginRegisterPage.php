<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Pages\Abstract\AdminSettingsPage;
use Filament\Pages\Page;

class AdminLoginRegisterPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.admin.pages.settings-login-register';

    protected static ?string $title = 'Login & Register';

    protected static string $description = 'Configure your login and registration settings';
}
