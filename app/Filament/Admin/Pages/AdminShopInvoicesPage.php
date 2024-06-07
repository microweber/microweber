<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Pages\Abstract\AdminSettingsPage;
use Filament\Pages\Page;

class AdminShopInvoicesPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.admin.pages.settings-shop-invoices';

    protected static ?string $title = 'Invoices';

    protected static string $description = 'Configure your shop invoices settings';

    protected static ?string $navigationGroup = 'Shop Settings';


}
