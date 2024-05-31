<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Pages\Abstract\SettingsPageDefault;
use Filament\Pages\Page;

class SettingsShopOther extends SettingsPageDefault
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.admin.pages.settings-shop-other';

    protected static ?string $title = 'Other';

    protected static string $description = 'Configure your shop other settings';

    protected static ?string $navigationGroup = 'Shop Settings';


}
