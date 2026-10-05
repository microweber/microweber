<?php

namespace Modules\Log\Filament;

use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Log\Filament\LogResource\Pages\ListLogs;
use Modules\Log\Models\Log;

class LogResource extends Resource
{
    protected static ?string $model = Log::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static string|\UnitEnum|null $navigationGroup = 'System Settings';

    protected static ?int $navigationSort = 50;

    protected static ?string $navigationLabel = 'Logs';

    protected static ?string $recordTitleAttribute = 'message';

    public static string $description = 'System and application log entries';

    public function getDescription(): string
    {
        return static::$description;
    }

    /** Read-only viewer — entries are written by the app, not created by hand. */
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    protected static function levelColor(?string $level): string
    {
        return match (strtolower((string) $level)) {
            'emergency', 'alert', 'critical', 'error' => 'danger',
            'warning' => 'warning',
            'notice', 'info' => 'info',
            'debug' => 'gray',
            default => 'gray',
        };
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('logged_at')
                    ->label('Time')
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->tooltip(fn ($record) => (string) ($record->logged_at ?? $record->created_at)),
                Tables\Columns\TextColumn::make('level')
                    ->label('Level')
                    ->badge()
                    ->color(fn (?string $state): string => static::levelColor($state))
                    ->sortable(),
                Tables\Columns\TextColumn::make('channel')
                    ->label('Channel')
                    ->badge()
                    ->color('gray')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('message')
                    ->label('Message')
                    ->limit(80)
                    ->tooltip(fn ($state) => $state)
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('rel_type')
                    ->label('Related')
                    ->formatStateUsing(fn ($state, $record) => trim(($state ?? '') . ' ' . ($record->rel_id ?? '')) ?: '—')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('level')
                    ->options(fn () => Log::query()
                        ->whereNotNull('level')->distinct()->orderBy('level')
                        ->pluck('level', 'level')->all()),
                Tables\Filters\SelectFilter::make('channel')
                    ->options(fn () => Log::query()
                        ->whereNotNull('channel')->distinct()->orderBy('channel')
                        ->pluck('channel', 'channel')->all()),
            ])
            ->actions([
                \Filament\Actions\ViewAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Entry')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('level')
                            ->badge()
                            ->color(fn (?string $state): string => static::levelColor($state)),
                        TextEntry::make('channel')->badge()->color('gray'),
                        TextEntry::make('logged_at')->label('Logged at')->dateTime(),
                        TextEntry::make('rel_type')->label('Related type')->placeholder('—'),
                        TextEntry::make('rel_id')->label('Related ID')->placeholder('—'),
                        TextEntry::make('created_at')->label('Created')->dateTime(),
                    ]),
                Section::make('Message')
                    ->schema([
                        TextEntry::make('message')
                            ->hiddenLabel()
                            ->prose()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLogs::route('/'),
        ];
    }
}
