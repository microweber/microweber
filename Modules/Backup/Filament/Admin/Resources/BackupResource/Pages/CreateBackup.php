<?php

namespace Modules\Backup\Filament\Admin\Resources\BackupResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Backup\Filament\Admin\BackupResource;
use Modules\Backup\Models\Backup;
use Modules\Backup\Support\GenerateBackup;
use Filament\Notifications\Notification;
use MicroweberPackages\Export\SessionStepper;
use Livewire\Attributes\On;
use Filament\Support\Exceptions\Halt;
use Illuminate\Contracts\View\View;

class CreateBackup extends CreateRecord
{
    protected static string $resource = BackupResource::class;
    public $progress = 0;
    public $isBackingUp = false;
    public $sessionId;

    public function mount(): void
    {

        parent::mount();
    }

    protected function handleRecordCreation(array $data): Backup
    {
        $this->isBackingUp = true;


         try {
            $this->sessionId = SessionStepper::generateSessionId(1);

            $exportData = [
                'format' => $data['format'] ?? 'json',
                'include_media' => $data['include_media'] ?? false,
                'include_modules' => $data['include_modules'] ?? false,
                'session_id' => $this->sessionId,
            ];

            // Add selected tables to export data
            if (isset($data['tables']) && !empty($data['tables'])) {
                $exportData['tables'] = $data['tables'];
            }

            if (!$data['include_media']) {
                $exportData['exclude_tables'] = ['media'];
            }

            if (!$data['include_modules']) {
                $exportData['exclude_folders'] = ['modules'];
            }

            $generateBackup = new GenerateBackup();
            $generateBackup->setSessionId($this->sessionId);

            $generateBackup->setType($exportData['format']);
            $generateBackup->setExportFileName($data['filename'] ?? 'backup-' . date('Y-m-d-H-i-s'));
            // $generateBackup->setExportWithZip(true);
            //  $generateBackup->setExportAllData(true);

            $generateBackup->setExportData('tables', $exportData['tables'] ?? []);
            $result = $generateBackup->start();

            $filename = $result['data']['filename'] ?? null;
            $filepath = $result['data']['filepath'] ?? null;

            if (isset($result['success']) && $filename) {
                Notification::make()
                    ->title('Backup created successfully')
                    ->success()
                    ->send();
                Backup::refreshFromDisk();
                $backup = new Backup();
//
//                $backup->filepath = $filepath;
//                $backup->filename = $filename;
//                $backup->size = 0;
//                $backup->created_at = now();
//                $backup->save();

                return $backup->where('filename', $filename)->first();
            }
            throw new \Exception('Failed to create backup');

        } catch (\Exception $e) {
            Notification::make()
                ->title('Failed to create backup')
                ->danger()
                ->body($e->getMessage())
                ->send();
            throw new Halt();
        } finally {
            $this->isBackingUp = false;
        }
    }


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
