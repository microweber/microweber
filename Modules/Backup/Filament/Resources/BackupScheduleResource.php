<?php

namespace Modules\Backup\Filament\Resources;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Backup\Filament\Resources\BackupScheduleResource\Pages;
use Modules\Backup\Models\BackupSchedule;

class BackupScheduleResource extends Resource
{
    protected static ?string $model = BackupSchedule::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-calendar';

    protected static string | \UnitEnum | null $navigationGroup = 'System Settings';

    protected static string $description = 'Configure automated backup schedules';

    protected static ?int $navigationSort = 10;

    public static function getDescription(): string
    {
        return static::$description;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        $details = [];

        if ($record->type) {
            $details['Type'] = BackupSchedule::getBackupTypeOptions()[$record->type] ?? $record->type;
        }

        if ($record->frequency) {
            $details['Frequency'] = BackupSchedule::getFrequencyOptions()[$record->frequency] ?? $record->frequency;
        }

        return $details;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Section::make('Schedule Details')
                    ->icon('heroicon-m-calendar')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Schedule Name')
                            ->placeholder('e.g., Daily Content Backup')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('type')
                            ->label('Backup Type')
                            ->options(BackupSchedule::getBackupTypeOptions())
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn ($state, Forms\Set $set) =>
                                $set('type', $state)
                            ),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Backup Options')
                    ->icon('heroicon-m-archive-box')
                    ->schema([
                        Forms\Components\Toggle::make('include_media')
                            ->label('Include Media Files')
                            ->onIcon('heroicon-m-check')
                            ->offIcon('heroicon-m-x-mark')
                            ->default(true)
                            ->helperText('Include uploaded media files in the backup'),

                        Forms\Components\CheckboxList::make('tables')
                            ->label('Tables to Backup')
                            ->options(fn () => self::getDatabaseTables())
                            ->columns(4)
                            ->visible(fn (Forms\Get $get) => $get('type') === 'customBackup')
                            ->required(fn (Forms\Get $get) => $get('type') === 'customBackup')
                            ->helperText('Select which database tables to include in the backup'),
                    ]),

                Forms\Components\Section::make('Frequency & Retention')
                    ->icon('heroicon-m-clock')
                    ->schema([
                        Forms\Components\Select::make('frequency')
                            ->label('Frequency')
                            ->options(BackupSchedule::getFrequencyOptions())
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn ($state, Forms\Set $set) =>
                                $set('frequency', $state)
                            ),

                        Forms\Components\TextInput::make('time')
                            ->label('Time')
                            ->type('time')
                            ->helperText('Time of day to run the backup (for daily, weekly, monthly)')
                            ->visible(fn (Forms\Get $get) => in_array($get('frequency'), ['daily', 'weekly', 'monthly'])),

                        Forms\Components\Select::make('day_of_week')
                            ->label('Day of Week')
                            ->options(BackupSchedule::getDayOfWeekOptions())
                            ->helperText('Day of the week to run the backup')
                            ->visible(fn (Forms\Get $get) => $get('frequency') === 'weekly'),

                        Forms\Components\Select::make('day_of_month')
                            ->label('Day of Month')
                            ->options(array_combine(range(1, 31), range(1, 31)))
                            ->helperText('Day of the month to run the backup')
                            ->visible(fn (Forms\Get $get) => $get('frequency') === 'monthly'),

                        Forms\Components\TextInput::make('retention_days')
                            ->label('Retention Days')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(365)
                            ->default(7)
                            ->helperText('Number of days to keep backups before automatic deletion'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->onIcon('heroicon-m-check')
                            ->offIcon('heroicon-m-x-mark')
                            ->default(true)
                            ->helperText('Enable or disable this backup schedule'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Schedule Status')
                    ->icon('heroicon-m-signal')
                    ->schema([
                        Forms\Components\DateTimePicker::make('last_run_at')
                            ->label('Last Run')
                            ->disabled()
                            ->hidden(fn ($state) => !$state),

                        Forms\Components\DateTimePicker::make('next_run_at')
                            ->label('Next Run')
                            ->disabled()
                            ->hidden(fn ($state) => !$state),
                    ])
                    ->columns(2)
                    ->visible(fn ($record) => $record !== null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => BackupSchedule::getBackupTypeOptions()[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        'contentBackup' => 'primary',
                        'fullBackup' => 'success',
                        'customBackup' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('frequency')
                    ->label('Frequency')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => BackupSchedule::getFrequencyOptions()[$state] ?? $state),

                Tables\Columns\TextColumn::make('time')
                    ->label('Time')
                    ->formatStateUsing(fn (?string $state): string => $state ?? '--:--'),

                Tables\Columns\TextColumn::make('retention_days')
                    ->label('Retention')
                    ->badge()
                    ->formatStateUsing(fn (int $state): string => $state . ' days'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                Tables\Columns\TextColumn::make('last_run_at')
                    ->label('Last Run')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('next_run_at')
                    ->label('Next Run')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options(BackupSchedule::getBackupTypeOptions()),

                Tables\Filters\SelectFilter::make('frequency')
                    ->options(BackupSchedule::getFrequencyOptions()),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                Tables\Actions\Action::make('runNow')
                    ->label('Run Now')
                    ->icon('heroicon-o-play')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Run Backup Now')
                    ->modalDescription('Are you sure you want to run this backup schedule immediately?')
                    ->action(function (BackupSchedule $record) {
                        // Dispatch backup job
                        app(\Modules\Backup\Services\AutomatedBackupService::class)->executeSchedule($record);
                    }),

                Tables\Actions\EditAction::make(),

                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('activate')
                        ->label('Activate')
                        ->icon('heroicon-m-check')
                        ->action(fn ($records) =>
                            $records->each->update(['is_active' => true])
                        ),

                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Deactivate')
                        ->icon('heroicon-m-x-mark')
                        ->action(fn ($records) =>
                            $records->each->update(['is_active' => false])
                        ),
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
            'index' => Pages\ListBackupSchedules::route('/'),
            'create' => Pages\CreateBackupSchedule::route('/create'),
            'edit' => Pages\EditBackupSchedule::route('/{record}/edit'),
        ];
    }

    /**
     * Get database tables for custom backup selection.
     *
     * @return array<string, string>
     */
    private static function getDatabaseTables(): array
    {
        $tables = [];
        $tableList = app()->database_manager->get_tables_list();

        foreach ($tableList as $table) {
            $tableWithPrefix = str_replace(app()->database_manager->get_prefix(), '', $table);
            $tables[$tableWithPrefix] = $tableWithPrefix;
        }

        return $tables;
    }
}
