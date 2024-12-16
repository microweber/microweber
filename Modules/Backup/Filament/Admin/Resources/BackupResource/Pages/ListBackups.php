<?php

namespace Modules\Backup\Filament\Admin\Resources\BackupResource\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
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

            // Add upload action
            Action::make('upload')
                ->label('Upload Backup')
                ->icon('heroicon-m-arrow-up-tray')
                ->form([
                    FileUpload::make('backup_file')
                        ->label('Backup File')
                        ->disk('local')
                        ->directory('backups')
                        ->acceptedFileTypes([
                            'application/zip',
                            'application/json',
                        ])
                        ->required()
                        ->helperText('Supported formats: .zip, .json')
                ])
                ->action(function (array $data) {
                    try {
                        $file = $data['backup_file'];

                        // Get backup location
                        $backupLocation = backup_location();
                        if (!is_dir($backupLocation)) {
                            mkdir($backupLocation, 0755, true);
                        }

                        // Get original filename
                        $filename = basename($file);
                        $filePath = $backupLocation . $filename;

                        // Check if file already exists
                        if (file_exists($filePath)) {
                            $filename = pathinfo($filename, PATHINFO_FILENAME) . '_' . time() . '.' . pathinfo($filename, PATHINFO_EXTENSION);
                            $filePath = $backupLocation . $filename;
                        }

                        // Move file from temporary upload location to backup location
                        if (rename(storage_path('app/' . $file), $filePath)) {
                            // Create backup record
                            Backup::create([
                                'filename' => $filename,
                                'filepath' => $filePath,
                                'size' => filesize($filePath)
                            ]);

                            Notification::make()
                                ->title('Backup uploaded successfully')
                                ->success()
                                ->send();
                        } else {
                            throw new \Exception('Failed to move uploaded file');
                        }
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Upload failed')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
            CreateAction::make()
                ->label('Create Backup')
                ->icon('heroicon-m-plus'),
        ];
    }
}
