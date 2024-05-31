<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Pages\Abstract\SettingsPageDefault;
use Filament\Pages\Page;

class SettingsShopPayments extends SettingsPageDefault
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.admin.pages.settings-shop-payments';

    protected static ?string $title = 'Payments';

    protected static string $description = 'Configure your shop payments settings';

    protected static ?string $navigationGroup = 'Shop Settings';


}
