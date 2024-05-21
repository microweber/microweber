<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;

class SimpleTestPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.admin.pages.simple-test-page';
}
