<?php

namespace Modules\Backup\Filament\Resources\BackupScheduleResource\Pages;

use Modules\Backup\Filament\Resources\BackupScheduleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBackupSchedule extends CreateRecord
{
    protected static string $resource = BackupScheduleResource::class;

    protected function afterCreate(): void
    {
        // Calculate next run time after creation
        $this->record->calculateNextRun();
    }
}
