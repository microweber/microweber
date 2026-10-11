<?php

namespace MicroweberPackages\Monitoring\Filament\Resources;

use MicroweberPackages\Monitoring\Models\ErrorTracking;
use Filament\TextInput;
use Filament\Textarea;
use Filament\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ErrorTrackingResource extends Resource
{
    protected static ?string $model = ErrorTracking::class;
    protected static ?string $recordTitleAttribute = 'message';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-bug-ant';

    protected static string | \UnitEnum | null $navigationGroup = 'System Settings';

    protected static ?string $navigationLabel = 'Error Tracking';

    protected static ?string $breadcrumb = 'Error Tracking';

    protected static ?int $navigationSort = 90;

    public static function getGloballySearchableAttributes(): array
    {
        return ['message', 'exception_class', 'file', 'url'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return \Illuminate\Support\Str::limit($record->message ?? 'Error #' . $record->id, 80);
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->level) {
            $details['Level'] = ucfirst($record->level);
        }

        if ($record->exception_class) {
            $details['Exception'] = class_basename($record->exception_class);
        }

        return $details;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Error Details')
                    ->icon('heroicon-m-exclamation-triangle')
                    ->schema([
                        TextInput::make('level')
                            ->label('Level')
                            ->disabled(),

                        Textarea::make('message')
                            ->label('Message')
                            ->disabled()
                            ->rows(3)
                            ->columnSpanFull(),

                        TextInput::make('exception_class')
                            ->label('Exception Class')
                            ->disabled(),

                        TextInput::make('location')
                            ->label('Location')
                            ->disabled()
                            ->hint('File and line number'),
                    ])
                    ->columns(2),

                Section::make('Context')
                    ->icon('heroicon-m-information-circle')
                    ->schema([
                        TextInput::make('url')
                            ->label('URL')
                            ->disabled()
                            ->columnSpan(2),

                        TextInput::make('method')
                            ->label('HTTP Method')
                            ->disabled(),

                        TextInput::make('user_ip')
                            ->label('IP Address')
                            ->disabled(),

                        TextInput::make('user_agent')
                            ->label('User Agent')
                            ->disabled()
                            ->columnSpanFull(),

                        Textarea::make('trace')
                            ->label('Stack Trace')
                            ->disabled()
                            ->rows(10)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsed(),

                Section::make('Resolution')
                    ->icon('heroicon-m-check-circle')
                    ->schema([
                        Toggle::make('is_resolved')
                            ->label('Mark as Resolved')
                            ->live(),

                        Textarea::make('resolution_notes')
                            ->label('Resolution Notes')
                            ->placeholder('Describe how this error was resolved...')
                            ->rows(3)
                            ->visible(fn ($get) => $get('is_resolved')),
                    ])
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('level')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'critical' => 'danger',
                        'error' => 'danger',
                        'warning' => 'warning',
                        'notice' => 'warning',
                        'info' => 'info',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('message')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->message)
                    ->searchable(),

                Tables\Columns\TextColumn::make('exception_class')
                    ->label('Exception')
                    ->limit(30)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('location')
                    ->label('Location')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('url')
                    ->label('URL')
                    ->limit(40)
                    ->toggleable()
                    ->tooltip(fn ($record) => $record->url),

                Tables\Columns\TextColumn::make('user_id')
                    ->label('User')
                    ->formatStateUsing(fn ($state) => $state ? 'User #' . $state : 'Guest')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('user_ip')
                    ->label('IP')
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_resolved')
                    ->label('Resolved')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('occurrence_count')
                    ->label('Count')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => $state > 10 ? 'danger' : 'gray'),

                Tables\Columns\TextColumn::make('last_occurred_at')
                    ->label('Last Occurrence')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('last_occurred_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('level')
                    ->options([
                        'critical' => 'Critical',
                        'error' => 'Error',
                        'warning' => 'Warning',
                        'notice' => 'Notice',
                        'info' => 'Info',
                    ]),

                Tables\Filters\TernaryFilter::make('is_resolved')
                    ->label('Status')
                    ->trueLabel('Resolved')
                    ->falseLabel('Unresolved')
                    ->native(false),

                Tables\Filters\Filter::make('today')
                    ->label('Today')
                    ->query(fn (Builder $query) => $query->whereDate('created_at', today())),

                Tables\Filters\Filter::make('this_week')
                    ->label('This Week')
                    ->query(fn (Builder $query) => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])),

                Tables\Filters\Filter::make('this_month')
                    ->label('This Month')
                    ->query(fn (Builder $query) => $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])),

                Tables\Filters\Filter::make('file')
                    ->label('File')
                    ->form([
                        TextInput::make('file')
                            ->placeholder('Filter by file name...'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['file'])) {
                            $query->where('file', 'like', '%' . $data['file'] . '%');
                        }
                    }),
            ])
            ->actions([
                \Filament\Actions\EditAction::make()
                    ->label('Review & Resolve'),

                \Filament\Actions\Action::make('markResolved')
                    ->label('Mark Resolved')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => !$record->is_resolved)
                    ->requiresConfirmation()
                    ->modalHeading('Mark Error as Resolved')
                    ->modalDescription('Are you sure you want to mark this error as resolved?')
                    ->action(fn ($record) => $record->markAsResolved()),

                \Filament\Actions\Action::make('markUnresolved')
                    ->label('Mark Unresolved')
                    ->icon('heroicon-o-x-circle')
                    ->color('warning')
                    ->visible(fn ($record) => $record->is_resolved)
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->update(['is_resolved' => false, 'resolved_at' => null])),

                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\BulkAction::make('markResolved')
                        ->label('Mark as Resolved')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->markAsResolved()),

                    \Filament\Actions\BulkAction::make('markUnresolved')
                        ->label('Mark as Unresolved')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['is_resolved' => false])),

                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                \Filament\Actions\Action::make('refresh')
                    ->label('Refresh')
                    ->icon('heroicon-o-arrow-path')
                    ->action(fn () => null),
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
            'index' => \MicroweberPackages\Monitoring\Filament\Resources\ErrorTrackingResource\Pages\ListErrorTracking::class,
            'edit' => \MicroweberPackages\Monitoring\Filament\Resources\ErrorTrackingResource\Pages\EditErrorTracking::class,
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes();
    }

    public static function getNavigationBadge(): ?string
    {
        try {
            return static::getModel()::unresolved()->count() ?: null;
        } catch (\Throwable $e) {
            // Missing table (fresh install / partial DB) must not white-screen the admin.
            return null;
        }
    }

    public static function getNavigationBadgeColor(): ?string
    {
        try {
            $count = static::getModel()::unresolved()->count();
        } catch (\Throwable $e) {
            return null;
        }

        if ($count === 0) {
            return null;
        }

        if ($count > 10) {
            return 'danger';
        }

        return 'warning';
    }
}
