<?php

namespace Modules\Backup\Filament\Resources\BackupResource\Pages;

use Modules\Backup\Filament\Pages\CreateBackup;
use Modules\Backup\Filament\Resources\BackupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBackups extends ListRecords
{
    protected static string $resource = BackupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('create')
                ->label('Create Backup')
                ->url(CreateBackup::getNavigationUrl()),
        ];
    }
}
