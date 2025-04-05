<?php

namespace Modules\Backup\Filament\Resources;

use Filament\Forms\Components\View;
use Filament\Forms\Components\Wizard;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;
use Livewire\Livewire;
use Modules\Backup\Filament\Resources\BackupResource\Pages;
use Modules\Backup\Filament\Resources\BackupResource\RelationManagers;
use Modules\Backup\Models\Backup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Modules\Backup\SessionStepper;

class BackupResource extends Resource
{
    protected static ?string $navigationGroup = 'System';

    protected static ?string $model = Backup::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-uturn-left';

    protected static ?int $navigationSort = 9999;

    public static $sessionId = null;
    private static $restoreFile = null;

    public static $restoreType = null;

    public static function form(Form $form): Form
    {
        return $form
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
                Tables\Actions\Action::make('restore')
                    ->label('Restore')
                    ->modalCancelAction(false)
                    ->modalSubmitAction(false)
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

                Tables\Actions\Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn ($record) => route('admin.backup.download', ['file' => $record->filename]))
                    ->openUrlInNewTab(),

                Tables\Actions\Action::make('delete')
                    ->label('Delete')
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation()
                    ->action(fn ($record) => unlink(backup_location() . $record->filename)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                unlink(backup_location() . $record->filename);
                            }
                        }),
                ]),
            ]);
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
