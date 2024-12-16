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
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
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
            Select::make('format')
                ->options([
                    'json' => 'JSON',
                    'zip' => 'ZIP',
                    'xml' => 'XML',
                ])
                ->required()
                ->default('json'),

            Toggle::make('include_media')
                ->label('Include Media Files')
                ->default(true),

            Toggle::make('include_modules')
                ->label('Include Modules')
                ->default(true),
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
                    ->formatStateUsing(fn ($state) => format_bytes($state))
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
            ])

            ->actions([
                Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->action(fn (Backup $record) => response()->download($record->filepath)),

                Action::make('restore')
                    ->label('Restore')
                    ->icon('heroicon-m-arrow-path')
                    ->requiresConfirmation()
                    ->action(function (Backup $record) {
                        return $record->restore($record->filepath);
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
