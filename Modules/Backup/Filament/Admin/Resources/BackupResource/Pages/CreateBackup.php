<?php

namespace Modules\Backup\Filament\Admin\Resources\BackupResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Backup\Filament\Admin\BackupResource;
use Modules\Backup\Models\Backup;
use Filament\Notifications\Notification;

class CreateBackup extends CreateRecord
{
    protected static string $resource = BackupResource::class;

    protected function handleRecordCreation(array $data): Backup
    {
        $backup = new Backup();

        $exportData = [
            'format' => $data['format'],
            'include_media' => $data['include_media'] ?? false,
            'include_modules' => $data['include_modules'] ?? false,
        ];

        if (!$data['include_media']) {
            $exportData['exclude_tables'] = ['media'];
        }

        if (!$data['include_modules']) {
            $exportData['exclude_folders'] = ['modules'];
        }

        $result = $backup->generateBackup($exportData);

        $filename = $result['data']['filename'] ?? null;

        if (isset($result['success']) and $filename) {
            Notification::make()
                ->title('Backup created successfully')
                ->success()
                ->send();

            // Refresh the rows to include the new backup
        //    $backup->getRows();

            // Find and return the newly created backup
            //return $backup->where('filename',$filename)->first();
            $this->halt();
        }

        Notification::make()
            ->title('Failed to create backup')
            ->danger()
            ->send();

        $this->halt();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
