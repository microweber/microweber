<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'mw-dashboard';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = 'Dashboard';

    protected static string $view = 'filament.admin.pages.dashboard';
}
