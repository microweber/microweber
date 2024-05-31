<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Pages\Abstract\SettingsPageDefault;
use Filament\Pages\Page;

class SettingsShopInvoices extends SettingsPageDefault
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.admin.pages.settings-shop-invoices';

    protected static ?string $title = 'Invoices';

    protected static string $description = 'Configure your shop invoices settings';

    protected static ?string $navigationGroup = 'Shop Settings';


}
