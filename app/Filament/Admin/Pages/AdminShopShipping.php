<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Pages\Abstract\AdminSettingsPage;
use Filament\Pages\Page;

class AdminShopShipping extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.admin.pages.settings-shop-shipping';

    protected static ?string $title = 'Shipping';

    protected static string $description = 'Configure your shop shipping settings';

    protected static ?string $navigationGroup = 'Shop Settings';


}
