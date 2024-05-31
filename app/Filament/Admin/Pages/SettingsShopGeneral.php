<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Pages\Abstract\SettingsPageDefault;
use Filament\Pages\Page;

class SettingsShopGeneral extends SettingsPageDefault
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.admin.pages.settings-shop-general';

    protected static ?string $title = 'General';

    protected static string $description = 'Configure your shop general settings';

    protected static ?string $navigationGroup = 'Shop Settings';


}
