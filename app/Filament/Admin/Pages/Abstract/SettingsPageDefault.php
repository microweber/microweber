<?php

namespace App\Filament\Admin\Pages\Abstract;

use Filament\Pages\Page;

abstract class SettingsPageDefault extends Page
{
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $description = '';

    public function getDescription(): string
    {
        return static::$description;
    }
}
