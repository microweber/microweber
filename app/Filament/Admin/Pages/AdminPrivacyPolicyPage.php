<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Pages\Abstract\AdminSettingsPage;
use Filament\Pages\Page;

class AdminPrivacyPolicyPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.admin.pages.settings-privacy-policy';

    protected static ?string $title = 'Privacy Policy';

    protected static string $description = 'Configure your privacy policy settings';
}
