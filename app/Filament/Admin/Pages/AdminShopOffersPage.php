<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Pages\Abstract\AdminSettingsPage;
use Filament\Pages\Page;

class AdminShopOffersPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.admin.pages.settings-shop-offers';

    protected static ?string $title = 'Offers';

    protected static string $description = 'Configure your shop offers settings';

    protected static ?string $navigationGroup = 'Shop Settings';


}
