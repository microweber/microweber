<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Pages\Abstract\SettingsPageDefault;
use Filament\Pages\Page;

class SettingsShopShipping extends SettingsPageDefault
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.admin.pages.settings-shop-shipping';

    protected static ?string $title = 'Shipping';

    protected static string $description = 'Configure your shop shipping settings';

    protected static ?string $navigationGroup = 'Shop Settings';


}
