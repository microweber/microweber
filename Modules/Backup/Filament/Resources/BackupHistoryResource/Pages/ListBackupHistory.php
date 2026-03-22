<?php

namespace Modules\Backup\Filament\Resources\BackupHistoryResource\Pages;

use Modules\Backup\Filament\Resources\BackupHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBackupHistory extends ListRecords
{
    protected static string $resource = BackupHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('backupNow')
                ->label('Create Backup')
                ->icon('heroicon-o-plus')
                ->url(fn () => route('filament.admin.resources.backup-schedules.create')),
        ];
    }
}
