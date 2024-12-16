<?php

namespace Modules\Backup\Filament\Admin;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Filament\Forms\Form;
use Modules\Backup\Models\Backup;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use MicroweberPackages\Export\SessionStepper;
use Modules\Backup\Support\Restore;
use Modules\Backup\Filament\Admin\Resources\BackupResource\Pages;

class BackupResource extends Resource
{
    protected static ?string $model = Backup::class;
    //protected static ?string $navigationIcon = 'heroicon-o-archive';
    protected static ?string $navigationGroup = 'System';
    protected static ?string $slug = 'backups';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Backup Options')
                ->schema([
                    Forms\Components\TextInput::make('filename')
                        ->label('Filename')
                        ->default(function () {
                            return 'backup-' . date('Y-m-d-H-i-s');
                        })
                        ->placeholder('backup')
                        ->required(),

                    Forms\Components\TextInput::make('steps')
                        ->label('steps')
                        ->default(5)
                        ->placeholder('100')
                        ->required(),

                    Toggle::make('include_media')
                        ->label('Include Media Files')
                        ->default(true),

                    Toggle::make('include_modules')
                        ->label('Include Modules')
                        ->default(true),
                ]),

            Section::make('Tables')
                ->schema([
                    CheckboxList::make('tables')
                        ->label('Select Tables to Backup')
                        ->options(function () {
                            $tables = [];
                            $skipTables = [
                                'migrations',
                                'personal_access_tokens',
                                'sessions',
                                'cache',
                                'jobs',
                                'failed_jobs',
                                'backups',
                                'login_attempts',
                                'log',
                                'jobs',
                                'job_batches',
                                'imports',
                                'exports',
                                'password_resets',
                                'failed_jobs'
                            ];

                            // Get all tables using Schema facade
                            $allTables = app()->database_manager->get_tables_list();
                            $prefix = DB::getTablePrefix();
                            foreach ($allTables as $tableInfo) {
                                $tableName = array_values((array)$tableInfo)[0];
                                $tableName = str_replace_first($prefix, '', $tableName);
                                if (!in_array($tableName, $skipTables)) {
                                    $tables[$tableName] = $tableName;
                                }
                            }

                            return $tables;
                        })
                        ->columns(3)
                        ->searchable()
                        ->bulkToggleable()
                        ->helperText('Select the tables you want to include in the backup.')
                ])
                ->collapsible(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('filename')
                    ->label('Backup File')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('size')
                    ->label('Size')
                    ->formatStateUsing(fn($state) => format_bytes($state))
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([


            ])
            ->actions([
                Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->action(fn(Backup $record) => response()->download($record->filepath)),

                Action::make('restore')
                    ->label('Restore')
                    ->icon('heroicon-m-arrow-path')
                    ->requiresConfirmation()
                    ->action(function (Backup $record) {
                        try {

                            $sessionId = SessionStepper::generateSessionId(1);
                            $restore = new Restore();
                            $restore->setSessionId($sessionId);
                            $restore->setFile($record->filepath);
                            $restore->setOvewriteById(true);
                            $restore->setToDeleteOldContent(false);

                            $result = $restore->start();

                            if (isset($result['error'])) {
                                throw new \Exception($result['error']);
                            }

                            Notification::make()
                                ->title('Backup restored successfully')
                                ->success()
                                ->send();

                            return $result;
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Restore failed')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),

                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBackups::route('/'),
            'create' => Pages\CreateBackup::route('/create'),
        ];
    }
}
