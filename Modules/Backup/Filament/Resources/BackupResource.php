<?php

namespace Modules\Backup\Filament\Resources;

use Filament\Forms\Components\View;
use Filament\Forms\Components\Wizard;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;
use Livewire\Livewire;
use MicroweberPackages\Export\SessionStepper;
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

class BackupResource extends Resource
{
    protected static ?string $model = Backup::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static $sessionId = null;
    private static $restoreFile = null;

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
                                    RadioDeck::make('restore_type')
                                        ->label('Restore Type')
                                        ->descriptions([
                                            'delete_all' => 'Delete all website content & restore',
                                            'overwrite' => 'Overwrite the website content from backup',
                                            'overwrite_by_names' => 'Try to overwrite content by Names & Titles',
                                        ])
                                        ->icons([
                                            'delete_all' => 'heroicon-o-trash',
                                            'overwrite' => 'heroicon-o-arrow-path',
                                            'overwrite_by_names' => 'heroicon-o-arrow-down-on-square-stack',
                                        ])
                                        ->options([
                                            'delete_all' => 'Delete & Restore',
                                            'overwrite' => 'Overwrite',
                                            'overwrite_by_names' => 'Overwrite by Names & Titles',
                                        ])
                                        ->default('content_backup')
                                        ->required()
                                ])->afterValidation(function ($livewire, $record) {
                                    self::$restoreFile = $record->filename;
                                    self::$sessionId = SessionStepper::generateSessionId(20);
                                    $livewire->dispatch('restoreIsStarted', sessionId: self::$sessionId, restoreFile: self::$restoreFile);
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
