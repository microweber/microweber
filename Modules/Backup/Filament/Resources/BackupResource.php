<?php

namespace Modules\Backup\Filament\Resources;

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
