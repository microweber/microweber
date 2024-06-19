<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Pages\Abstract\AdminSettingsPage;
use Filament\Pages\Page;

class AdminShopOtherPage extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'mw-general';

    protected static string $view = 'filament.admin.pages.settings-shop-other';

    protected static ?string $title = 'Other';

    protected static string $description = 'Configure your shop other settings';

    protected static ?string $navigationGroup = 'Shop Settings';


}