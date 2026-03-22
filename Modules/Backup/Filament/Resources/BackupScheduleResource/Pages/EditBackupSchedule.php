<?php

namespace Modules\Backup\Filament\Resources\BackupScheduleResource\Pages;

use Modules\Backup\Filament\Resources\BackupScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBackupSchedule extends EditRecord
{
    protected static string $resource = BackupScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        // Recalculate next run time after update
        $this->record->calculateNextRun();
    }
}
