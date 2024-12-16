<?php

namespace Modules\Backup\Filament\Admin\Resources\BackupResource\Pages;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Modules\Backup\Filament\Admin\BackupResource;
use Filament\Actions\CreateAction;
use Modules\Backup\Models\Backup;

class ListBackups extends ListRecords
{
    protected static string $resource = BackupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('refresh')
                ->label('Refresh Backups')
                ->icon('heroicon-m-arrow-path')
                ->action(function () {
                    Backup::refreshFromDisk();
                    Notification::make()
                        ->title('Backups refreshed successfully')
                        ->success()
                        ->send();
                }),
            CreateAction::make()
                ->label('Create Backup')
                ->icon('heroicon-m-plus'),
        ];
    }
}
