<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Pages\Abstract\SettingsPageDefault;
use Filament\Pages\Page;

class SettingsShopCoupons extends SettingsPageDefault
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.admin.pages.settings-shop-coupons';

    protected static ?string $title = 'Coupons';

    protected static string $description = 'Configure your shop coupons settings';

    protected static ?string $navigationGroup = 'Shop Settings';

}
