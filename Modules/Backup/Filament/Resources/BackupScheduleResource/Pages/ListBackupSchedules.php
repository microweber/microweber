<?php

namespace Modules\Backup\Filament\Resources\BackupScheduleResource\Pages;

use Modules\Backup\Filament\Resources\BackupScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBackupSchedules extends ListRecords
{
    protected static string $resource = BackupScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
