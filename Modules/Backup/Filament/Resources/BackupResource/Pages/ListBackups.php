<?php

namespace Modules\Backup\Filament\Resources\BackupResource\Pages;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\View;
use Filament\Forms\Components\Wizard;
use Filament\Support\Enums\MaxWidth;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;
use Livewire\Attributes\Url;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use Modules\Backup\Backup;
use Modules\Backup\Filament\Resources\BackupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Backup\SessionStepper;
use Modules\Restore\Restore;

class ListBackups extends ListRecords
{
    protected static string $resource = BackupResource::class;

    public $sessionId = null;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('upload_backup')
                ->label('Upload Backup')
                ->icon('heroicon-o-arrow-up-tray')
                ->form([
                    FileUpload::make('backupFile')
                        ->label('Backup File')
                        ->placeholder('Select backup file'),
                ])->afterFormValidated(function () {

                }),
            Actions\Action::make('create_backup')
                ->modalSubmitAction(false)
                ->modalCancelAction(false)
                ->icon('heroicon-o-inbox-arrow-down')
                ->modalWidth(MaxWidth::MaxContent)
                ->form($this->backupFormArray())
        ];
    }


    public function backupFormArray()
    {
        return [
            Wizard::make([
            Wizard\Step::make('Backup Type')
                ->description('Choose the type of backup you want to create')
                ->schema([
                    RadioDeck::make('backup_type')
                        ->label('Backup Type')
                        ->descriptions([
                            'content_backup' => 'Create backup of your sites without sensitive information. This will create a zip with live-edit css, media, post categories &amp; pages.',
                            'custom_backup' => 'Create backup with custom selected tables, users, api_keys, media, modules, templates...',
                            'full_backup' => 'Create full backup of your sites with all data. This will create a zip with all data from your database. Include sensitive information like users, passwords, api keys, settings.',
                        ])
                        ->icons([
                            'content_backup'=>'heroicon-o-newspaper',
                            'custom_backup'=>'heroicon-o-cog',
                            'full_backup'=>'heroicon-o-inbox-arrow-down',
                        ])
                        ->options([
                            'content_backup' => 'Content Backup',
                            'custom_backup' => 'Custom Backup',
                            'full_backup' => 'Full Backup',
                        ])
                        ->default('content_backup')
                        ->required()
                ]),

            Wizard\Step::make('Custom Options')
                ->description('Select what to include in your backup')
                ->schema([
                    Section::make('Database Tables')
                        ->schema([
                            Toggle::make('include_tables')
                                ->label('Include Tables')
                                ->onIcon('heroicon-m-check')
                                ->offIcon('heroicon-m-x-mark'),
                        ])
                        ->visible(fn (callable $get) => $get('backup_type') === 'custom_backup'),

                    Section::make('Media Files')
                        ->schema([
                            Toggle::make('include_media')
                                ->label('Include Media Files')
                                ->onIcon('heroicon-m-check')
                                ->offIcon('heroicon-m-x-mark'),
                        ])
                        ->visible(fn (callable $get) => $get('backup_type') === 'custom_backup'),

                    Section::make('Modules')
                        ->schema([
                            Toggle::make('include_modules')
                                ->label('Include Modules')
                                ->onIcon('heroicon-m-check')
                                ->offIcon('heroicon-m-x-mark'),
                        ])
                        ->visible(fn (callable $get) => $get('backup_type') === 'custom_backup'),

                    Section::make('Templates')
                        ->schema([
                            Toggle::make('include_templates')
                                ->label('Include Templates')
                                ->onIcon('heroicon-m-check')
                                ->offIcon('heroicon-m-x-mark'),
                        ])
                        ->visible(fn (callable $get) => $get('backup_type') === 'custom_backup'),
                ])
                ->visible(fn (callable $get) => $get('backup_type') === 'custom_backup'),

            Wizard\Step::make('Backup details')
                ->description('Configure and generate your backup')
                ->schema([
                    TextInput::make('backup_filename')
                        ->label('Backup Filename')
                        ->placeholder('Enter backup filename (optional)')
                        ->helperText('Leave empty for auto-generated filename'),
                ])->afterValidation(function () {
                    rmdir_recursive(backup_cache_location());
                    $this->sessionId = SessionStepper::generateSessionId(20);
                    $this->dispatch('backupIsStarted', sessionId: $this->sessionId);
                }),

            Wizard\Step::make('Creating backup')
                ->description('Your backup is being created')
                ->schema([
                    View::make('backup_progress')
                        ->view('modules.backup::filament.pages.create-backup-progress')
                        ->viewData([
                            'sessionId' => $this->sessionId,
                        ]),
                ])

          ])
    ];
    }


    public function runRestoreStep($params) {

        if (!isset($params['sessionId'])) {
            return false;
        }
        if (!isset($params['restoreFile'])) {
            return false;
        }
        if (!isset($params['restoreType'])) {
            return false;
        }

        $restoreFile = backup_location() . $params['restoreFile'];

        // START RESTORE
        $restore = new Restore();
        $restore->setSessionId($params['sessionId']);
        $restore->setFile($restoreFile);

        if ($params['restoreType'] == 'delete_all') {
            $restore->setToDeleteOldContent(true);
            $restore->setToDeleteOldCssFiles(true);
        } else if ($params['restoreType'] == 'overwrite_by_id') {
            $restore->setOvewriteById(true);
            $restore->setWriteOnDatabase(true);
        } else if ($params['restoreType'] == 'overwrite_by_titles') {
            $restore->setOvewriteById(false);
            $restore->setWriteOnDatabase(true);
        }

        $restore->setBatchRestoring(true);

        return $restore->start();

    }

    public function runBackupStep($sessionId) {

        // START BACKUP
        $backup = new Backup();
        $backup->setSessionId($sessionId);

        $backupByType = 'full';
        $backupFilename = 'backup_' . date('Y-m-d_H-i-s');

        if ($backupByType == 'custom') {

//            $includeMedia = false;
//            if ($request->get('include_media', false) == 1) {
//                $includeMedia = true;
//            }
//
//            $backup->setAllowSkipTables(false);
//            $backup->setExportTables($request->get('include_tables', []));
//            $backup->setExportMedia($includeMedia);
//            $backup->setExportModules($request->get('include_modules', []));
//            $backup->setExportTemplates($request->get('include_templates', []));
        } else if ($backupByType == 'full') {

            $backup->setAllowSkipTables(false); // skip sensitive tables
            $backup->setBackupAllData(true);
            $backup->setBackupMedia(true);
            $backup->setBackupWithZip(true);

        } else {
            $backup->setType('json');
            $backup->setAllowSkipTables(true); // skip sensitive tables
            $backup->setBackupAllData(true);
            $backup->setBackupMedia(true);
            $backup->setBackupWithZip(true);
        }

        if (!empty($backupFilename)) {
            $backup->setBackupFileName($backupFilename);
        }

     return $backup->start();

    }
}
