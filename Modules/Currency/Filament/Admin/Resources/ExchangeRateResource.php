<?php

namespace Modules\Currency\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Modules\Currency\Models\ExchangeRate;
use Modules\Currency\Models\Currency;
use Modules\Currency\Filament\Admin\Resources\ExchangeRateResource\Pages;
use Modules\Currency\Services\CurrencyConversionService;

use MicroweberPackages\FilamentRegistry\GlobalSearch\MicroweberGloballySearchable;
class ExchangeRateResource extends Resource
{
    protected static ?string $model = ExchangeRate::class;
    protected static ?string $recordTitleAttribute = 'from_currency';

    protected static string | \BackedEnum | null $navigationIcon = null;

    protected static string | \UnitEnum | null $navigationGroup = 'Shop Settings';

    protected static ?int $navigationSort = 11;
    use MicroweberGloballySearchable;

    public static function getGloballySearchableAttributes(): array
    {
        return ['from_currency', 'to_currency', 'source'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->from_currency . ' → ' . $record->to_currency;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->rate) {
            $details['Rate'] = (string) $record->rate;
        }

        if ($record->source) {
            $details['Source'] = $record->source;
        }

        return $details;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                \Filament\Schemas\Components\Section::make('Exchange Rate Details')
                    ->description('Define the conversion rate between two currencies')
                    ->schema([
                        Forms\Components\Select::make('from_currency')
                            ->label('From Currency')
                            ->required()
                            ->options(function () {
                                return Currency::active()
                                    ->pluck('name', 'code')
                                    ->map(function ($name, $code) {
                                        return "{$name} ({$code})";
                                    })
                                    ->toArray();
                            })
                            ->searchable()
                            ->hint('The source currency'),

                        Forms\Components\Select::make('to_currency')
                            ->label('To Currency')
                            ->required()
                            ->options(function () {
                                return Currency::active()
                                    ->pluck('name', 'code')
                                    ->map(function ($name, $code) {
                                        return "{$name} ({$code})";
                                    })
                                    ->toArray();
                            })
                            ->searchable()
                            ->hint('The target currency')
                            ->different('from_currency'),

                        Forms\Components\TextInput::make('rate')
                            ->required()
                            ->numeric()
                            ->step(0.00000001)
                            ->minValue(0.00000001)
                            ->placeholder('1.23456789')
                            ->hint('Exchange rate value (e.g., 1 USD = 0.85 EUR)'),

                        Forms\Components\Select::make('source')
                            ->label('Rate Source')
                            ->required()
                            ->options([
                                'manual' => 'Manual Entry',
                                'api' => 'External API',
                                'import' => 'Import',
                            ])
                            ->default('manual'),
                    ])
                    ->columns(2),

                \Filament\Schemas\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->hint('Inactive rates won\'t be used for conversions'),

                        Forms\Components\Placeholder::make('last_updated_display')
                            ->label('Last Updated')
                            ->content(function (?ExchangeRate $record): string {
                                return $record?->last_updated 
                                    ? $record->last_updated->format('Y-m-d H:i:s')
                                    : 'Never';
                            }),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('from_currency')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('to_currency')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('rate')
                    ->numeric(decimalPlaces: 8)
                    ->sortable(),

                Tables\Columns\TextColumn::make('inverse_rate')
                    ->numeric(decimalPlaces: 8)
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('source')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'manual' => 'gray',
                        'api' => 'primary',
                        'import' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),

                Tables\Columns\TextColumn::make('last_updated')
                    ->dateTime()
                    ->sortable()
                    ->since(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->trueLabel('Active Only')
                    ->falseLabel('Inactive Only')
                    ->native(false),

                Tables\Filters\SelectFilter::make('from_currency')
                    ->label('From Currency')
                    ->options(function () {
                        return Currency::active()->pluck('name', 'code');
                    })
                    ->searchable(),

                Tables\Filters\SelectFilter::make('to_currency')
                    ->label('To Currency')
                    ->options(function () {
                        return Currency::active()->pluck('name', 'code');
                    })
                    ->searchable(),

                Tables\Filters\Filter::make('stale_rates')
                    ->label('Stale Rates (24h+)')
                    ->query(function ($query) {
                        return $query->where('last_updated', '<', now()->subHours(24));
                    }),
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
                \Filament\Actions\Action::make('refreshRate')
                    ->label('Refresh')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Refresh Exchange Rate')
                    ->modalDescription('Update this exchange rate from external API?')
                    ->visible(fn (ExchangeRate $record): bool => $record->source === 'api')
                    ->action(function (ExchangeRate $record) {
                        // This would integrate with an exchange rate API
                        \Filament\Notifications\Notification::make()
                            ->title('Rate Refresh')
                            ->body('Exchange rate refresh triggered.')
                            ->info()
                            ->send();
                    }),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                    \Filament\Actions\BulkAction::make('refreshRates')
                        ->label('Refresh Selected')
                        ->icon('heroicon-o-arrow-path')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            // Clear cache for selected rates
                            $conversionService = app(CurrencyConversionService::class);
                            foreach ($records as $record) {
                                $conversionService->clearCache(
                                    $record->from_currency,
                                    $record->to_currency
                                );
                            }
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Cache Cleared')
                                ->body('Exchange rate cache cleared for selected rates.')
                                ->success()
                                ->send();
                        }),
                ]),
            ])
            ->defaultSort('last_updated', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExchangeRates::route('/'),
            'create' => Pages\CreateExchangeRate::route('/create'),
            'edit' => Pages\EditExchangeRate::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Exchange Rates';
    }

    public static function getModelLabel(): string
    {
        return 'Exchange Rate';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Exchange Rates';
    }
}
