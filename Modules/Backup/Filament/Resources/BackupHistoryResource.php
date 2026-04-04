<?php

namespace Modules\Backup\Filament\Resources;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Backup\Filament\Resources\BackupHistoryResource\Pages;
use Modules\Backup\Models\BackupHistory;

class BackupHistoryResource extends Resource
{
    protected static ?string $model = BackupHistory::class;

    protected static ?string $recordTitleAttribute = 'filename';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-clock';

    protected static string | \UnitEnum | null $navigationGroup = 'System Settings';

    protected static ?int $navigationSort = 11;

    public static function getGloballySearchableAttributes(): array
    {
        return ['filename'];
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        $details = [];

        if ($record->type) {
            $details['Type'] = BackupHistory::getTypeOptions()[$record->type] ?? $record->type;
        }

        if ($record->status) {
            $details['Status'] = BackupHistory::getStatusOptions()[$record->status] ?? $record->status;
        }

        return $details;
    }

    public static function getNavigationLabel(): string
    {
        return 'Backup History';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Section::make('Backup Information')
                    ->icon('heroicon-m-archive-box')
                    ->schema([
                        Forms\Components\TextInput::make('filename')
                            ->disabled(),

                        Forms\Components\Select::make('type')
                            ->options(BackupHistory::getTypeOptions())
                            ->disabled(),

                        Forms\Components\Select::make('backup_type')
                            ->options(BackupHistory::getBackupTypeOptions())
                            ->disabled(),

                        Forms\Components\Select::make('status')
                            ->options(BackupHistory::getStatusOptions())
                            ->disabled(),

                        Forms\Components\TextInput::make('size')
                            ->formatStateUsing(fn ($state) => $state ? format_bytes($state) : 'N/A')
                            ->disabled(),

                        Forms\Components\DateTimePicker::make('started_at')
                            ->disabled(),

                        Forms\Components\DateTimePicker::make('completed_at')
                            ->disabled(),

                        Forms\Components\Textarea::make('error_message')
                            ->label('Error Details')
                            ->disabled()
                            ->columnSpanFull()
                            ->visible(fn ($record) => $record && $record->status === 'failed'),
                    ])
                    ->columns(2),
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

                Tables\Columns\BadgeColumn::make('type')
                    ->label('Type')
                    ->colors([
                        'primary' => 'manual',
                        'success' => 'scheduled',
                    ]),

                Tables\Columns\BadgeColumn::make('backup_type')
                    ->label('Backup Type')
                    ->formatStateUsing(fn (string $state): string => BackupHistory::getBackupTypeOptions()[$state] ?? $state)
                    ->colors([
                        'primary' => 'contentBackup',
                        'success' => 'fullBackup',
                        'warning' => 'customBackup',
                    ]),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn (string $state): string => BackupHistory::getStatusOptions()[$state] ?? $state)
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'running',
                        'success' => 'completed',
                        'danger' => 'failed',
                    ]),

                Tables\Columns\TextColumn::make('size')
                    ->label('Size')
                    ->formatStateUsing(fn (?int $state): string => $state ? format_bytes($state) : 'N/A')
                    ->sortable(),

                Tables\Columns\TextColumn::make('started_at')
                    ->label('Started')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Completed')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options(BackupHistory::getTypeOptions()),

                Tables\Filters\SelectFilter::make('backup_type')
                    ->options(BackupHistory::getBackupTypeOptions()),

                Tables\Filters\SelectFilter::make('status')
                    ->options(BackupHistory::getStatusOptions()),

                Tables\Filters\Filter::make('created_at')
                    ->label('Created Between')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')->label('From'),
                        Forms\Components\DatePicker::make('created_until')->label('Until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['created_from'] ?? null, fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'] ?? null, fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn ($record) => $record->filepath ? route('admin.backup.download', ['file' => basename($record->filepath)]) : null)
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => $record->status === 'completed' && $record->fileExists()),

                Tables\Actions\Action::make('restore')
                    ->label('Restore')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Restore Backup')
                    ->modalDescription('Are you sure you want to restore this backup? This will overwrite your current content.')
                    ->visible(fn ($record) => $record->status === 'completed' && $record->fileExists()),

                Tables\Actions\ViewAction::make()
                    ->visible(fn ($record) => $record->status === 'failed'),

                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListBackupHistory::route('/'),
            'view' => Pages\ViewBackupHistory::route('/{record}'),
        ];
    }

    /**
     * Get navigation badge count for running backups.
     */
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::running()->count() ?: null;
    }

    /**
     * Get navigation badge color.
     */
    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }
}
