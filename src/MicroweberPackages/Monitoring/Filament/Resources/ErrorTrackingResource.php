<?php

namespace MicroweberPackages\Monitoring\Filament\Resources;

use MicroweberPackages\Monitoring\Models\ErrorTracking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ErrorTrackingResource extends Resource
{
    protected static ?string $model = ErrorTracking::class;

    protected static ?string $navigationIcon = 'heroicon-o-bug-ant';

    protected static ?string $navigationGroup = 'System';

    protected static ?string $navigationLabel = 'Error Tracking';

    protected static ?string $breadcrumb = 'Error Tracking';

    protected static ?int $navigationSort = 90;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Error Details')
                    ->schema([
                        Forms\Components\TextInput::make('level')
                            ->label('Level')
                            ->disabled(),

                        Forms\Components\Textarea::make('message')
                            ->label('Message')
                            ->disabled()
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('exception_class')
                            ->label('Exception Class')
                            ->disabled(),

                        Forms\Components\TextInput::make('location')
                            ->label('Location')
                            ->disabled()
                            ->hint('File and line number'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Context')
                    ->schema([
                        Forms\Components\TextInput::make('url')
                            ->label('URL')
                            ->disabled()
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('method')
                            ->label('HTTP Method')
                            ->disabled(),

                        Forms\Components\TextInput::make('user_ip')
                            ->label('IP Address')
                            ->disabled(),

                        Forms\Components\TextInput::make('user_agent')
                            ->label('User Agent')
                            ->disabled()
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('trace')
                            ->label('Stack Trace')
                            ->disabled()
                            ->rows(10)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsed(),

                Forms\Components\Section::make('Resolution')
                    ->schema([
                        Forms\Components\Toggle::make('is_resolved')
                            ->label('Mark as Resolved')
                            ->live(),

                        Forms\Components\Textarea::make('resolution_notes')
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
                        Forms\Components\TextInput::make('file')
                            ->placeholder('Filter by file name...'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['file'])) {
                            $query->where('file', 'like', '%' . $data['file'] . '%');
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Review & Resolve'),

                Tables\Actions\Action::make('markResolved')
                    ->label('Mark Resolved')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => !$record->is_resolved)
                    ->requiresConfirmation()
                    ->modalHeading('Mark Error as Resolved')
                    ->modalDescription('Are you sure you want to mark this error as resolved?')
                    ->action(fn ($record) => $record->markAsResolved()),

                Tables\Actions\Action::make('markUnresolved')
                    ->label('Mark Unresolved')
                    ->icon('heroicon-o-x-circle')
                    ->color('warning')
                    ->visible(fn ($record) => $record->is_resolved)
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->update(['is_resolved' => false, 'resolved_at' => null])),

                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('markResolved')
                        ->label('Mark as Resolved')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->markAsResolved()),

                    Tables\Actions\BulkAction::make('markUnresolved')
                        ->label('Mark as Unresolved')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['is_resolved' => false])),

                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\Action::make('refresh')
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
        return static::getModel()::unresolved()->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $count = static::getModel()::unresolved()->count();
        
        if ($count === 0) {
            return null;
        }
        
        if ($count > 10) {
            return 'danger';
        }
        
        return 'warning';
    }
}
