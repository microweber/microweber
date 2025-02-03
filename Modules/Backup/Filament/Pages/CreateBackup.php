<?php

namespace Modules\Backup\Filament\Pages;

use Filament\Pages\Page;

class CreateBackup extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'backup::filament.pages.create-backup';
}
