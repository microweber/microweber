<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Pages\Abstract\AdminSettingsPage;
use Filament\Pages\Page;

class AdminShopGeneral extends AdminSettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.admin.pages.settings-shop-general';

    protected static ?string $title = 'General';

    protected static string $description = 'Configure your shop general settings';

    protected static ?string $navigationGroup = 'Shop Settings';


}
