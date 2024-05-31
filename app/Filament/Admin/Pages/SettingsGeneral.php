<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Pages\Abstract\SettingsPageDefault;
use Filament\Pages\Page;

class SettingsGeneral extends SettingsPageDefault
{

    protected static ?string $slug = 'settings/general';

    protected static string $view = 'filament.admin.pages.settings-general';

    protected static ?string $title = 'General';

    protected static string $description = 'Make basic settings for your website.';
    protected static ?string $navigationGroup = 'Website Settings';

}
