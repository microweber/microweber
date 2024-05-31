<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;

class SettingsShopTaxes extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.admin.pages.settings-shop-taxes';

    protected static ?string $title = 'Taxes';

    protected static string $description = 'Configure your shop taxes settings';

    protected static ?string $navigationGroup = 'Shop Settings';


}

