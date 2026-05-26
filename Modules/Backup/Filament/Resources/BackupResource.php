<?php

namespace Modules\Backup\Filament\Resources;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Schemas\Components\View;
use Filament\Schemas\Components\Wizard;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;
use Livewire\Livewire;
use Modules\Backup\Filament\Resources\BackupResource\Pages;
use Modules\Backup\Filament\Resources\BackupResource\RelationManagers;
use Modules\Backup\Models\Backup;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Modules\Backup\SessionStepper;

class BackupResource extends Resource
{
    // task-2026-05-22-f83bf6 / AI-764 — surfaces the Backup admin page at
    // /admin/backup and adds it to the Settings sidebar.
    protected static string | \UnitEnum | null $navigationGroup = 'System Settings';

    protected static ?string $model = Backup::class;

    protected static ?string $recordTitleAttribute = 'filename';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationLabel = 'Backup';

    protected static ?int $navigationSort = 50;

    public static function getGloballySearchableAttributes(): array
    {
        return ['filename'];
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        $details = [];

        if ($record->date) {
            $details['Date'] = $record->date;
        }

        if ($record->size) {
            $details['Size'] = number_format($record->size / 1024, 1) . ' KB';
        }

        return $details;
    }

    public static $sessionId = null;
    private static $restoreFile = null;

    public static $restoreType = null;
    public static string $description = 'Manage your backups, restore content, and download backup files';
    public function getDescription(): string
    {

        return static::$description;
    }

    public static function getSlug(?\Filament\Panel $panel = null): string
    {
        return 'backup';
    }


    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('filename')
                    ->label('Filename')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('date')
                    ->label('Date')
                    ->sortable(),

                Tables\Columns\TextColumn::make('time')
                    ->label('Time')
                    ->sortable(),

                Tables\Columns\TextColumn::make('size')
                    ->label('Size')
                    ->formatStateUsing(fn (string $state): string => format_bytes($state))
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('restore')
                    ->label('Restore')
                    // ->modalCancelAction(false)
                    ->modalSubmitAction(false)
                    ->closeModalByClickingAway(false)
                    ->form([
                        Wizard::make([
                            Wizard\Step::make('Restore type')
                                ->description('How do you like to restore your content?')
                                ->schema([
                                    RadioDeck::make('restoreType')
                                        ->live()
                                        ->label('Restore Type')
                                        ->descriptions([
                                            'deleteAll' => 'Delete all website content & restore',
                                            'overwriteById' => 'Overwrite the website content from backup',
                                            'overwriteByTitles' => 'Try to overwrite content by Names & Titles',
                                        ])
                                        ->icons([
                                            'deleteAll' => 'heroicon-o-trash',
                                            'overwriteById' => 'heroicon-o-arrow-path',
                                            'overwriteByTitles' => 'heroicon-o-arrow-down-on-square-stack',
                                        ])
                                        ->options([
                                            'deleteAll' => 'Delete & Restore',
                                            'overwriteById' => 'Overwrite',
                                            'overwriteByTitles' => 'Overwrite by Names & Titles',
                                        ])
                                        ->required()

                                ])->afterValidation(function ($livewire, $record, Forms\Get $get) {

                                    self::$sessionId = SessionStepper::generateSessionId(20, [
                                        'restoreFile' => $record->filename,
                                        'restoreType' => $get('restoreType'),
                                    ]);

                                    $livewire->dispatch('restoreIsStarted',
                                        sessionId: self::$sessionId
                                    );
                                }),

                            Wizard\Step::make('Restore')
                                ->description('Start restoring your backup')
                                ->schema([
                                    View::make('restore_progress')
                                        ->view('modules.backup::filament.pages.restore-backup-progress')
                                        ->viewData([
                                            'sessionId' => self::$sessionId,
                                        ]),
                                ]),
                        ]),
                    ])
                    ->icon('heroicon-o-arrow-uturn-left'),

                Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn ($record) => route('admin.backup.download', ['file' => $record->filename]))
                    ->openUrlInNewTab(),

                DeleteAction::make()
                    ->label('Delete')
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $filepath = backup_location() . $record->filename;
                        if (file_exists($filepath)) {
                            unlink($filepath);
                        }
                        $record->delete();
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $filepath = backup_location() . $record->filename;
                                if (file_exists($filepath)) {
                                    unlink($filepath);
                                }
                                $record->delete();
                            }
                        }),
                ]),
            ])
            ->emptyStateHeading('No backups yet')
            ->emptyStateDescription('Create a backup to safeguard your website content and settings.')
            ->emptyStateIcon('heroicon-o-archive-box');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBackups::route('/')
        ];
    }
}
