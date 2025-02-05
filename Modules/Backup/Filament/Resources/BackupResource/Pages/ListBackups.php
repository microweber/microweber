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
use MicroweberPackages\Export\SessionStepper;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use Modules\Backup\Filament\Pages\CreateBackup;
use Modules\Backup\Filament\Resources\BackupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Backup\GenerateBackup;

class ListBackups extends ListRecords
{
    protected static string $resource = BackupResource::class;

    public $sessionId = null;
    public $backupFile = null;

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
                    dd($this->backupFile);
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
                    $this->dispatch('backupIsStarted');
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

    public function runBackupStep() {

        // START BACKUP
        $backup = new GenerateBackup();
        $backup->setSessionId($this->sessionId);
        $backup_by_type = 'full';
        $backup_filename = 'backup_' . date('Y-m-d_H-i-s');

        if ($backup_by_type == 'custom') {

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
        } else if ($backup_by_type == 'full') {

            $backup->setAllowSkipTables(false); // skip sensitive tables
            $backup->setExportAllData(true);
            $backup->setExportMedia(true);
            $backup->setExportWithZip(true);

        } else {
            $backup->setType('json');
            $backup->setAllowSkipTables(true); // skip sensitive tables
            $backup->setExportAllData(true);
            $backup->setExportMedia(true);
            $backup->setExportWithZip(true);
        }

        if (!empty($backup_filename)) {
            $backup->setExportFileName($backup_filename);
        }

        return $backup->start();

    }
}
