<?php

namespace Modules\Backup\Filament\Resources\BackupResource\Pages;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\View;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Notifications\Notification;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\Storage;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;
use Livewire\Livewire;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use Modules\Backup\Backup;
use Modules\Backup\Filament\Resources\BackupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Backup\SessionStepper;
use Modules\Restore\Restore;
use MicroweberPackages\Database\Facades\DatabaseManager;
use MicroweberPackages\Template\ContentJson\ContentJsonExporter;
use MicroweberPackages\Template\ContentJson\ContentJsonInstaller;
use Filament\Forms\Components\Select;

class ListBackups extends ListRecords
{
    protected static string $resource = BackupResource::class;

    public $sessionId = null;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('uploadBackup')
                ->label('Upload Backup')
                ->icon('heroicon-o-arrow-up-tray')
                ->modalSubmitAction(false)
                ->modalCancelAction(false)
                ->form([
                    MwFileUpload::make('backupFile')
                        ->live()
                        ->label('Backup File')
                        ->afterStateUpdated(function ($state) {
                            if (empty($state)) {
                                return;
                            }
                            $filePath = url2dir($state);
                            $filePath = basename($filePath);
                            $realPath = storage_path('app/public/') . $filePath;

                            // Move file to backup location
                            $backupLocation = backup_location();
                            rename($realPath, $backupLocation . $filePath);

                            $this->closeActionModal();
                            $this->dispatch('refreshTable');

                            Notification::make()
                                ->success()
                                ->title('Backup Uploaded Successfully')
                                ->send();
                        })
                        ->placeholder('Select backup file'),
                ]),
            Actions\Action::make('create_backup')
                ->modalSubmitAction(false)
                ->modalCancelAction(false)
                ->icon('heroicon-o-inbox-arrow-down')
                ->modalWidth(Width::MaxContent)
                ->form($this->backupFormArray()),

            // Export the site to the simplified, portable content.json format
            // (pages/posts/products/menus) — full site or a single template.
            Actions\Action::make('exportContentJson')
                ->label('Export content.json')
                ->icon('heroicon-o-document-arrow-down')
                ->color('gray')
                ->modalSubmitActionLabel('Download')
                ->form([
                    Select::make('template')
                        ->label('Scope')
                        ->options($this->contentJsonTemplateOptions())
                        ->placeholder('Full site (all templates)')
                        ->helperText('Leave empty to export the whole site, or pick a template to export only its content.'),
                    Toggle::make('includeProducts')->label('Include products')->default(true),
                    Toggle::make('includePosts')->label('Include posts')->default(true),
                    Toggle::make('includeMenus')->label('Include menus')->default(true),
                ])
                ->action(function (array $data) {
                    $exporter = new ContentJsonExporter();
                    $exporter->setTemplate($data['template'] ?? null)
                        ->setIncludeProducts((bool) ($data['includeProducts'] ?? true))
                        ->setIncludePosts((bool) ($data['includePosts'] ?? true))
                        ->setIncludeMenus((bool) ($data['includeMenus'] ?? true));
                    $json = $exporter->toJson();
                    $name = 'content-' . (($data['template'] ?? '') ?: 'site') . '-' . date('Y-m-d-His') . '.json';

                    return response()->streamDownload(function () use ($json) {
                        echo $json;
                    }, $name, ['Content-Type' => 'application/json']);
                }),

            // Import a simplified content.json file into the site.
            Actions\Action::make('importContentJson')
                ->label('Import content.json')
                ->icon('heroicon-o-document-arrow-up')
                ->color('gray')
                ->form([
                    MwFileUpload::make('contentJsonFile')
                        ->label('content.json file')
                        ->acceptExtensions(['json'])
                        ->required(),
                    Select::make('template')
                        ->label('Apply under template')
                        ->options($this->contentJsonTemplateOptions())
                        ->placeholder("Use the file's own values / current template"),
                    Select::make('importType')
                        ->label('Mode')
                        ->options([
                            'full' => 'Create / update content',
                            'delete' => 'Replace all content (delete existing pages/posts/products first)',
                        ])
                        ->default('full')
                        ->required(),
                ])
                ->action(function (array $data) {
                    $file = url2dir($data['contentJsonFile']);
                    if (!is_file($file)) {
                        Notification::make()->danger()->title('Uploaded file not found')->send();
                        return;
                    }
                    $installer = new ContentJsonInstaller();
                    $installer->setFile($file)
                        ->setTemplate($data['template'] ?? null)
                        ->setImportType($data['importType'] ?? 'full');

                    if (!$installer->hasContent()) {
                        Notification::make()->danger()->title('No importable content in file')->send();
                        return;
                    }
                    $result = $installer->install();
                    $this->dispatch('refreshTable');
                    Notification::make()
                        ->success()
                        ->title('content.json imported')
                        ->body(implode("\n", array_slice($result['log'] ?? [], 0, 8)))
                        ->send();
                }),
        ];
    }

    /** Template names available as export/import scope (core + extra templates). */
    protected function contentJsonTemplateOptions(): array
    {
        $dirs = array_merge(
            glob(base_path('Templates/*'), GLOB_ONLYDIR) ?: [],
            glob(base_path('ExtraTemplates/*'), GLOB_ONLYDIR) ?: []
        );
        $names = [];
        foreach ($dirs as $d) {
            $name = basename($d);
            $names[$name] = $name;
        }
        ksort($names);
        return $names;
    }


    public function backupFormArray()
    {

        $databaseTables = [];
        $databaseTablesList = DatabaseManager::get_tables_list();
        foreach ($databaseTablesList as $table) {
            $tableWithPrefix = str_replace(DatabaseManager::get_prefix(), '', $table);
            $databaseTables[$tableWithPrefix] = $tableWithPrefix;
        }

        return [
            Wizard::make([
                Wizard\Step::make('Backup Type')
                    ->description('Choose the type of backup you want to create')
                    ->schema([
                        RadioDeck::make('backupType')
                            ->label('Backup Type')
                            ->live()
                            ->descriptions([
                                'contentBackup' => 'Create backup of your sites without sensitive information. This will create a zip with live-edit css, media, post categories & pages.',
                                'customBackup' => 'Create backup with custom selected tables, users, api_keys, media, modules, templates...',
                                'fullBackup' => 'Create full backup of your sites with all data. This will create a zip with all data from your database. Include sensitive information like users, passwords, api keys, settings.',
                            ])
                            ->icons([
                                'contentBackup' => 'heroicon-o-newspaper',
                                'customBackup' => 'heroicon-o-cog',
                                'fullBackup' => 'heroicon-o-inbox-arrow-down',
                            ])
                            ->options([
                                'contentBackup' => 'Content Backup',
                                'customBackup' => 'Custom Backup',
                                'fullBackup' => 'Full Backup',
                            ])
                            ->required()
                    ]),

                Wizard\Step::make('Custom Options')
                    ->description('Select what to include in your backup')
                    ->schema([
                        Section::make('Database Tables')
                            ->icon('heroicon-m-circle-stack')
                            ->schema([
                                Toggle::make('includeTables')
                                    ->label('Include Tables')
                                    ->onIcon('heroicon-m-check')
                                    ->live()
                                    ->offIcon('heroicon-m-x-mark'),
                                Checkbox::make('includeAllTables')
                                    ->label('Include All Tables')
                                    ->live()
                                    ->afterStateUpdated(function ($state, Set $set) use ($databaseTables) {
                                        if ($state) {
                                            $set('tables', array_keys($databaseTables));
                                        } else {
                                            $set('tables', []);
                                        }
                                    })
                                    ->hidden(fn(callable $get) => !$get('includeTables')),
                                CheckboxList::make('tables')
                                    ->label('Tables')
                                    ->options($databaseTables)
                                    ->live()
                                    ->columns(4)
                                    ->required()
                                    ->hidden(fn(callable $get) => !$get('includeTables')),
                            ])
                            ->visible(fn(callable $get) => $get('backupType') === 'customBackup'),

                        Section::make('Media Files')
                            ->icon('heroicon-m-photo')
                            ->schema([
                                Toggle::make('includeMedia')
                                    ->live()
                                    ->label('Include Media Files')
                                    ->onIcon('heroicon-m-check')
                                    ->offIcon('heroicon-m-x-mark'),
                            ])
                            ->visible(fn(callable $get) => $get('backupType') === 'customBackup'),

//                        Section::make('Modules')
//                            ->schema([
//                                Toggle::make('includeModules')
//                                    ->live()
//                                    ->label('Include Modules')
//                                    ->onIcon('heroicon-m-check')
//                                    ->offIcon('heroicon-m-x-mark'),
//                            ])
//                            ->visible(fn(callable $get) => $get('backupType') === 'customBackup'),

//                        Section::make('Templates')
//                            ->schema([
//                                Toggle::make('includeTemplates')
//                                    ->live()
//                                    ->label('Include Templates')
//                                    ->onIcon('heroicon-m-check')
//                                    ->offIcon('heroicon-m-x-mark'),
//                            ])
//                            ->visible(fn(callable $get) => $get('backupType') === 'customBackup'),
                    ])
                    ->visible(fn(callable $get) => $get('backupType') === 'customBackup'),

                Wizard\Step::make('Backup details')
                    ->description('Configure and generate your backup')
                    ->schema([
                        TextInput::make('backupFilename')
                            ->live()
                            ->label('Backup Filename')
                            ->placeholder('Enter backup filename (optional)')
                            ->helperText('Leave empty for auto-generated filename'),
                    ])->afterValidation(function (Get $get) {
                        rmdir_recursive(backup_cache_location());
                        $this->sessionId = SessionStepper::generateSessionId(20, [
                            'backupType' => $get('backupType'),
                            'backupFilename' => $get('backupFilename'),
                            'includeTables' => $get('includeTables'),
                            'includeAllTables' => $get('includeAllTables'),
                            'tables' => $get('tables'),
                            'includeMedia' => $get('includeMedia'),
//                            'includeModules' => $get('includeModules'),
//                            'includeTemplates' => $get('includeTemplates'),
                        ]);
                        $this->dispatch('backupIsStarted', sessionId: $this->sessionId);
                    }),

                Wizard\Step::make('Creating backup')
                    ->description('Your backup is being created')
                    ->schema([
                        View::make('backup_progress')
                            ->view('modules.backup::filament.pages.create-backup-progress'),
                    ])

            ])
        ];
    }


    public function runRestoreStep($sessionId)
    {
        SessionStepper::setSessionId($sessionId);
        $getSession = SessionStepper::getSessionFileData();

        if (!isset($getSession['data']['restoreFile'])) {
            return false;
        }
        if (!isset($getSession['data']['restoreType'])) {
            return false;
        }

        $restoreFile = backup_location() . $getSession['data']['restoreFile'];

        // START RESTORE
        $restore = new Restore();
        $restore->setSessionId($sessionId);
        $restore->setFile($restoreFile);

        if ($getSession['data']['restoreType'] == 'deleteAll') {
            $restore->setWriteOnDatabase(true);
            $restore->setToDeleteOldContent(true);
            $restore->setToDeleteOldCssFiles(true);
            $restore->setOvewriteById(true);
        } else if ($getSession['data']['restoreType'] == 'overwriteById') {
            $restore->setOvewriteById(true);
            $restore->setWriteOnDatabase(true);
        } else if ($getSession['data']['restoreType'] == 'overwriteByTitles') {
            $restore->setOvewriteById(false);
            $restore->setWriteOnDatabase(true);
        }

        $restore->setBatchRestoring(true);

        return $restore->start();

    }

    public function runBackupStep($sessionId)
    {

        SessionStepper::setSessionId($sessionId);
        $getSession = SessionStepper::getSessionFileData();

        if (!isset($getSession['data']['backupType'])) {
            return false;
        }

        $backup = new Backup();
        $backup->setSessionId($sessionId);

        $backupByType = $getSession['data']['backupType'];

        if (isset($getSession['data']['backupFilename'])) {
            $backup->setBackupFileName($getSession['data']['backupFilename']);
        }

        if ($backupByType == 'customBackup') {

            if (isset($getSession['data']['includeMedia'])) {
                $backup->setBackupMedia($getSession['data']['includeMedia']);
            }
            if (isset($getSession['data']['includeTables'])) {
                $backup->setBackupTables($getSession['data']['tables']);
            }
//            if (isset($getSession['data']['includeModules'])) {
//                $backup->setBackupModules($getSession['data']['includeModules']);
//            }
//            if (isset($getSession['data']['includeTemplates'])) {
//                $backup->setBackupTemplates($getSession['data']['includeTemplates']);
//            }

            $backup->setAllowSkipTables(false);

        } else if ($backupByType == 'fullBackup') {

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

        return $backup->start();

    }
}
